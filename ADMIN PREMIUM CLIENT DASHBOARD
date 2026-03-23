<?php
/* Template Name: MTRS Ultimate Client Dashboard */
global $wpdb;
$current_user_id = get_current_user_id();

/** 
 * 1. ΕΛΕΓΧΟΣ ΠΡΟΣΒΑΣΗΣ
 */
if (!$current_user_id) {
    echo '
    <div style="display: flex; justify-content: center; align-items: center; min-height: 500px; padding: 20px; font-family: \'Inter\', sans-serif;">
        <div style="background: #ffffff; border-left: 6px solid #92d050; padding: 60px 40px; border-radius: 16px; text-align: center; max-width: 450px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05);">
            <div style="width: 50px; height: 40px; background: #92d050; border-radius: 6px; margin: 0 auto 30px; position: relative;">
                <div style="width: 26px; height: 25px; border: 4px solid #92d050; border-bottom: none; border-radius: 20px 20px 0 0; position: absolute; top: -18px; left: 8px;"></div>
                <div style="width: 6px; height: 6px; background: #fff; border-radius: 50%; position: absolute; top: 14px; left: 22px;"></div>
                <div style="width: 2px; height: 10px; background: #fff; position: absolute; top: 18px; left: 24px;"></div>
            </div>
            <h2 style="color: #000000; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; font-size: 1.4rem; font-weight: 900;">ΠΡΟΣΒΑΣΗ ΠΕΛΑΤΗ</h2>
            <a href="' . wp_login_url( get_permalink() ) . '" style="background: #000000; color: #ffffff; padding: 16px 45px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; display: inline-block; letter-spacing: 1px;">ΕΙΣΟΔΟΣ ΣΤΟ DASHBOARD</a>
        </div>
    </div>';
    return;
}

/** 
 * 2. DATABASE & ACTIONS LOGIC
 */
$balance = $wpdb->get_var($wpdb->prepare("SELECT balance FROM mtrs_client_balance WHERE user_id = %d", $current_user_id));
$balance = $balance ? floatval($balance) : 0;
$user_info = wp_get_current_user();

if (isset($_POST['mtrs_buy_now'])) {
    $price = floatval($_POST['service_price']);
    $s_name = sanitize_text_field($_POST['service_name']);
    $s_qty = isset($_POST['service_quantity']) ? intval($_POST['service_quantity']) : 1;
    if ($balance >= $price && $price > 0) {
        $wpdb->update('mtrs_client_balance', array('balance' => $balance - $price), array('user_id' => $current_user_id));
        $wpdb->insert('mtrs_active_services', array(
            'user_id' => $current_user_id, 'service_name' => $s_name, 'price_paid' => $price,
            'service_quantity' => $s_qty, 'expiry_date' => date('Y-m-d', strtotime('+1 year')),
            'purchase_date' => current_time('mysql')
        ));
        wp_mail(get_option('admin_email'), 'ΝΕΑ ΠΑΡΑΓΓΕΛΙΑ', "Ο χρήστης {$user_info->display_name} αγόρασε: $s_name ($price €)");
        wp_redirect(get_permalink()); exit;
    }
}

if (isset($_POST['mtrs_request_funds'])) {
    $amount = floatval($_POST['request_amount']);
    wp_mail(get_option('admin_email'), 'ΑΙΤΗΜΑ ΠΙΣΤΩΣΗΣ WALLET', "Ο πελάτης {$user_info->display_name} ζήτησε {$amount}€.");
    $msg_success = "Το αίτημα εστάλη επιτυχώς!";
}

if (isset($_POST['mtrs_send_ticket'])) {
    $subj = sanitize_text_field($_POST['ticket_subject']);
    $txt = sanitize_textarea_field($_POST['ticket_msg']);
    wp_mail(get_option('admin_email'), "SUPPORT: $subj", "Από: {$user_info->display_name}\n\n$txt");
    $msg_success = "Το μήνυμα εστάλη!";
}

$active_services = $wpdb->get_results($wpdb->prepare("SELECT * FROM mtrs_active_services WHERE user_id = %d ORDER BY purchase_date DESC", $current_user_id));

$total_active = 0; $expiring_soon = 0;
foreach($active_services as $s) {
    $total_active++;
    $days_left = (strtotime($s->expiry_date) - time()) / 86400;
    if($days_left > 0 && $days_left < 30) $expiring_soon++;
}

/** 
 * 3. ΡΥΘΜΙΣΕΙΣ ΚΑΤΑΣΤΗΜΑΤΟΣ & NEWS (ΤΩΡΑ ΑΠΟ ΤΟ ADMIN PANEL)
 */
$news_text = get_option('mtrs_news_text', 'ΝΕΑ ΥΠΗΡΕΣΙΑ GOOGLE ADS SETUP ΤΩΡΑ ΔΙΑΘΕΣΙΜΗ ΣΤΟ SHOP!');
$news_date = get_option('mtrs_news_date', 'MARCH 2024');



$main_services_list = ['SKROUTZ / XML', 'COURIER AUTO', 'ΤΡΑΠΕΖΙΚΗ ΣΥΝΔΕΣΗ', 'GOOGLE ADS SETUP', 'FB/IG SHOP SETUP', 'SPEED OPTIMIZATION', 'GDPR COMPLIANCE', 'NEWSLETTER AUTO', 'ADVANCED SECURITY', 'BASIC SUPPORT', 'STANDARD SUPPORT', 'VIP MAINTENANCE', 'PREMIUM HOSTING', 'DOMAIN .GR', 'SSL CERTIFICATE'];
$one_time_list = ['SKROUTZ / XML', 'COURIER AUTO', 'ΤΡΑΠΕΖΙΚΗ ΣΥΝΔΕΣΗ', 'GOOGLE ADS SETUP', 'GDPR COMPLIANCE', 'SPEED OPTIMIZATION'];

$all_sections = [
    ['id' => '02', 'cat' => 'dev', 'title' => 'ΔΙΑΣΥΝΔΕΣΕΙΣ & ΑΥΤΟΜΑΤΙΣΜΟΙ', 'items' => [
        ['name' => 'ΤΡΑΠΕΖΙΚΗ ΣΥΝΔΕΣΗ', 'price' => 120, 'desc' => 'Πρωτόκολλα 3D-Secure και σύνδεση με API.', 'icon' => '💳', 'type' => 'fixed', 'suffix' => '/ ΕΦΑΠΑΞ', 'theme' => 'theme-cyan'],
        ['name' => 'Skroutz XML', 'price' => 160, 'desc' => 'Πλήρως παραμετροποιημένο XML feed.', 'icon' => '📦', 'type' => 'fixed', 'suffix' => '/ ΕΦΑΠΑΞ', 'theme' => 'theme-purple']
    ]],
    ['id' => '03', 'cat' => 'data', 'title' => 'ΚΑΤΑΧΩΡΗΣΗ ΠΡΟΪΟΝΤΩΝ', 'items' => [
        ['name' => 'BASIC ENTRY', 'price' => 1.20, 'desc' => 'Καταχώρηση τίτλων.', 'icon' => '📝', 'type' => 'slider', 'unit' => 'ΠΡΟΪΟΝΤΑ', 'max' => 500, 's_id' => 'basic', 'theme' => 'theme-cyan']
    ]],
    ['id' => '04', 'cat' => 'sec', 'title' => 'ΥΠΟΣΤΗΡΙΞΗ & SECURITY', 'items' => [
        ['name' => 'ADVANCED SECURITY', 'price' => 90, 'desc' => 'Firewall (WAF) και Malware Protection.', 'icon' => '🔒', 'type' => 'fixed', 'suffix' => '/ ΕΤΟΣ', 'theme' => 'theme-pink']
    ]]
];
?>

<style>
    @import url('https://fonts.googleapis.com');
    .mtrs-body { background-color: #080a0f; color: #f8fafc; font-family: 'Inter', sans-serif; text-transform: uppercase; padding: 60px 20px; background-image: radial-gradient(circle at 50% 50%, #11141b 0%, #080a0f 100%); background-attachment: fixed; margin: 0; }
    .mtrs-container { max-width: 1200px; margin: 0 auto; }
    .wallet-bar { position: sticky; top: 20px; z-index: 100; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 40px; padding: 20px 40px; backdrop-filter: blur(30px); display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; box-shadow: 0 40px 100px rgba(0,0,0,0.6); }
    .req-btn { background: #92d050; color: #000; border: none; padding: 8px 15px; border-radius: 20px; font-size: 0.65rem; font-weight: 900; cursor: pointer; margin-left: 15px; transition: 0.3s; }
    .referral-info-box { background: rgba(0,212,255,0.05); border: 1px solid rgba(0,212,255,0.15); padding: 20px; border-radius: 20px; margin-bottom: 15px; }
    .top-ref-link { background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); padding: 8px 12px; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 12px 0; }
    .copy-mini-btn { background: #00d4ff; color: #000; border: none; padding: 4px 10px; border-radius: 5px; font-size: 0.6rem; font-weight: 900; cursor: pointer; }
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 40px; }
    .stat-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; text-align: center; }
    .stat-val { font-size: 1.5rem; font-weight: 900; color: #92d050; display: block; }
    .stat-label { font-size: 0.6rem; opacity: 0.5; letter-spacing: 1px; }
    .news-box { background: linear-gradient(90deg, rgba(146, 208, 80, 0.12), transparent); border-left: 4px solid #92d050; padding: 20px; border-radius: 0 15px 15px 0; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; }
    .category-header { font-size: 1.1rem; font-weight: 900; margin: 40px 0 20px; color: #92d050; border-bottom: 1px solid rgba(146, 208, 80, 0.2); padding-bottom: 10px; }
    .grid-layout { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
    .mtrs-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 25px; }
    .buy-btn { background: #fff; color: #000; border: none; padding: 12px; border-radius: 8px; font-weight: 900; cursor: pointer; width: 100%; margin-top: 10px; }
    #mtrsModal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.85); backdrop-filter:blur(5px); }
    .m-content { background:#11141b; margin:15% auto; padding:30px; width:90%; max-width:400px; border-radius:20px; border:1px solid #92d050; text-align:center; }
    .s-input { width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 10px; border-radius: 8px; margin-bottom: 10px; font-family: inherit; font-size: 0.8rem; }
    .theme-cyan { border-top: 4px solid #00d4ff; } .theme-purple { border-top: 4px solid #bc13fe; } .theme-pink { border-top: 4px solid #ff4d94; }
    .f-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 10px 20px; border-radius: 30px; cursor: pointer; font-size: 0.7rem; font-weight: 800; margin-right: 10px; }
    .f-btn.active { background: #92d050; color: #000; }
</style>

<div class="mtrs-body">
    <div class="mtrs-container">

        <!-- WALLET BAR -->
        <div class="wallet-bar">
            <div>
                <div style="font-weight: 800; font-size: 1.1rem;"><?php echo strtoupper($user_info->display_name); ?></div>
                <div style="opacity: 0.6; font-size: 0.7rem; margin-top: 4px;">PREMIUM CLIENT DASHBOARD</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.6rem; font-weight: 900;">
                    <?php echo number_format($balance, 2); ?>€
                    <button onclick="document.getElementById('mtrsModal').style.display='block'" class="req-btn">+ ΠΡΟΣΘΗΚΗ</button>
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div class="stats-row">
            <div class="stat-card">
                <span class="stat-val"><?php echo $total_active; ?></span>
                <span class="stat-label">ΕΝΕΡΓΕΣ ΥΠΗΡΕΣΙΕΣ</span>
            </div>
            <div class="stat-card">
                <span class="stat-val" style="color:<?php echo $expiring_soon > 0 ? '#ff4d4d' : '#92d050'; ?>;"><?php echo $expiring_soon; ?></span>
                <span class="stat-label">ΛΗΓΟΥΝ ΣΥΝΤΟΜΑ</span>
            </div>
            <div class="stat-card">
                <span class="stat-val"><?php echo count($active_services); ?></span>
                <span class="stat-label">ΣΥΝΟΛΟ ΑΓΟΡΩΝ</span>
            </div>
        </div>

        <div style="display: flex; gap: 40px; flex-wrap: wrap;">
            <!-- LEFT COLUMN -->
            <div style="flex: 2; min-width: 350px;">
                
                <!-- NEWS SECTION -->
                <div class="news-box">
                    <div>
                        <span style="background:#92d050; color:#000; padding:2px 8px; border-radius:4px; font-size:0.6rem; font-weight:900; margin-right:10px;">NEW</span>
                        <span style="font-size:0.8rem; font-weight:600;"><?php echo $news_text; ?></span>
                    </div>
                    <div style="font-size:0.7rem; opacity:0.5;"><?php echo $news_date; ?></div>
                </div>

                <div class="category-header">ΥΠΗΡΕΣΙΕΣ ΜΟΥ</div>
                <div class="grid-layout">
                    <?php if($active_services): foreach ($active_services as $service): 
                        if (!in_array(strtoupper($service->service_name), $main_services_list)) continue;
                        $is_one_time = in_array(strtoupper($service->service_name), $one_time_list);
                        $expiry_ts = strtotime($service->expiry_date);
                        $color = (max(0, min(100, (($expiry_ts - time()) / 86400))) > 30 || $is_one_time) ? '#92d050' : '#ff4d4d';
                    ?>
                        <div class="mtrs-card" style="border-left: 4px solid <?php echo $color; ?>;">
                            <div style="font-weight: 800; font-size: 0.9rem;"><?php echo strtoupper($service->service_name); ?></div>
                            <div style="font-size: 0.65rem; opacity: 0.6; margin-top: 5px;">
                                <?php echo $is_one_time ? 'ΜΟΝΙΜΑ ΕΝΕΡΓΗ' : 'ΛΗΞΗ: ' . date('d/m/Y', $expiry_ts); ?>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div style="flex: 1; min-width: 280px;">
                
                <div class="category-header">ΣΥΣΤΗΜΑ ΑΝΑΦΟΡΑΣ</div>
                <div class="referral-info-box">
                    <div style="font-size: 0.75rem; font-weight: 800; color: #00d4ff;">REFERRAL PROGRAM</div>
                    <p style="font-size: 0.65rem; opacity: 0.7; text-transform: none; margin: 8px 0; line-height: 1.4;">
                        ΣΥΣΤΗΣΤΕ ΕΝΑΝ ΦΙΛΟ ΣΑΣ ΣΤΗΝ MTRS ΚΑΙ ΜΟΛΙΣ ΟΛΟΚΛΗΡΩΣΕΙ ΤΗΝ ΠΡΩΤΗ ΤΟΥ ΑΓΟΡΑ, ΘΑ ΚΕΡΔΙΣΕΤΕ 10€ ΑΥΤΟΜΑΤΑ ΣΤΟ WALLET ΣΑΣ!
                    </p>
                    
                    <div class="top-ref-link">
                        <span id="refURL" style="font-size: 0.65rem; color: #fff; font-weight: 800; text-transform: none;">mtrs.gr/ref=<?php echo $current_user_id; ?></span>
                        <button class="copy-mini-btn" onclick="copyRef()">COPY</button>
                    </div>

                    <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; margin-top: 10px;">
                        <div style="font-weight: 900; font-size: 0.75rem;">CLIENT ID: #<?php echo $current_user_id; ?></div>
                        <div style="opacity: 0.5; font-size: 0.55rem;">(Ο ΠΡΟΣΩΠΙΚΟΣ ΣΑΣ ΚΩΔΙΚΟΣ ΑΝΑΓΝΩΡΙΣΗΣ)</div>
                    </div>
                </div>

                <div class="category-header">SUPPORT CENTER</div>
                <div style="background: rgba(146, 208, 80, 0.05); border: 1px dashed #92d050; padding: 20px; border-radius: 20px;">
                    <form method="POST">
                        <input type="text" name="ticket_subject" class="s-input" placeholder="ΘΕΜΑ" required>
                        <textarea name="ticket_msg" class="s-input" rows="3" placeholder="ΠΕΡΙΓΡΑΨΤΕ..." required></textarea>
                        <button type="submit" name="mtrs_send_ticket" class="buy-btn" style="background:#92d050; border:none;">ΑΠΟΣΤΟΛΗ</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SHOP -->
        <div class="category-header">SHOP / ΥΠΗΡΕΣΙΕΣ</div>
        <div style="margin-bottom:20px;">
            <button class="f-btn active" onclick="filterCat('all', this)">ΟΛΑ</button>
            <button class="f-btn" onclick="filterCat('dev', this)">ΔΙΑΣΥΝΔΕΣΕΙΣ</button>
            <button class="f-btn" onclick="filterCat('data', this)">ΚΑΤΑΧΩΡΗΣΗ</button>
        </div>

        <div class="grid-layout">
            <?php foreach ($all_sections as $sec): foreach ($sec['items'] as $item): ?>
                <div class="mtrs-card <?php echo $item['theme']; ?> shop-item" data-cat="<?php echo $sec['cat']; ?>">
                    <form method="POST">
                        <div style="font-size: 1.2rem; margin-bottom: 10px;"><?php echo $item['icon']; ?></div>
                        <h4 style="margin:0;"><?php echo $item['name']; ?></h4>
                        <p style="font-size: 0.7rem; opacity: 0.6; text-transform:none; min-height: 40px;"><?php echo $item['desc']; ?></p>
                        <?php if ($item['type'] === 'slider'): ?>
                            <div style="font-weight:900; margin:10px 0;"><span id="<?php echo $item['s_id']; ?>Price">0</span>€</div>
                            <input type="range" name="service_quantity" id="<?php echo $item['s_id']; ?>Range" min="0" max="<?php echo $item['max']; ?>" step="10" value="0" data-rate="<?php echo $item['price']; ?>" oninput="updateSlider('<?php echo $item['s_id']; ?>')" style="width:100%;">
                            <input type="hidden" name="service_price" id="<?php echo $item['s_id']; ?>HiddenPrice" value="0">
                        <?php else: ?>
                            <div style="font-weight:900; margin:10px 0;"><?php echo $item['price']; ?>€</div>
                            <input type="hidden" name="service_price" value="<?php echo $item['price']; ?>">
                            <input type="hidden" name="service_quantity" value="1">
                        <?php endif; ?>
                        <input type="hidden" name="service_name" value="<?php echo $item['name']; ?>">
                        <button type="submit" name="mtrs_buy_now" class="buy-btn" id="<?php echo ($item['type'] === 'slider') ? $item['s_id'].'Btn' : ''; ?>">ΑΓΟΡΑ</button>
                    </form>
                </div>
            <?php endforeach; endforeach; ?>
        </div>

    </div>
</div>

<div id="mtrsModal">
    <div class="m-content">
        <h3>ΑΙΤΗΜΑ ΠΙΣΤΩΣΗΣ</h3>
        <form method="POST">
            <input type="number" name="request_amount" class="s-input" placeholder="ΠΟΣΟ ΣΕ €" required>
            <button type="submit" name="mtrs_request_funds" class="buy-btn" style="background:#92d050;">ΑΠΟΣΤΟΛΗ</button>
            <button type="button" onclick="document.getElementById('mtrsModal').style.display='none'" class="buy-btn" style="background:transparent; color:#fff;">ΑΚΥΡΩΣΗ</button>
        </form>
    </div>
</div>

<script>
function updateSlider(id) {
    const r = document.getElementById(id + 'Range');
    const p = document.getElementById(id + 'Price');
    const hp = document.getElementById(id + 'HiddenPrice');
    const b = document.getElementById(id + 'Btn');
    const total = (parseInt(r.value) * parseFloat(r.getAttribute('data-rate'))).toFixed(2);
    p.innerText = total; hp.value = total;
    b.disabled = (total > <?php echo $balance; ?> || total == 0);
}
function filterCat(cat, btn) {
    document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.shop-item').forEach(i => {
        i.style.display = (cat === 'all' || i.getAttribute('data-cat') === cat) ? 'block' : 'none';
    });
}
function copyRef() {
    const text = document.getElementById('refURL').innerText;
    navigator.clipboard.writeText(text);
    alert('Referral Link Copied!');
}
</script>
