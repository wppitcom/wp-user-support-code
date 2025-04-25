# wp-user-support-code
كود بسيط لووردبريس يولّد رمز دعم فريد لكل مستخدم لتأكيد الملكية عند التواصل مع الدعم الفني أو مركز المساعدة.

# نظام توليد كود دعم فني فريد لكل مستخدم في ووردبريس

<p align="center">
  <img src="https://www.wppit.com/wp-content/uploads/2025/04/assets_task_01jse38vgmedhr3a0s023kc91m_img_0.webp" alt="wp-user-support-code preview" width="50%" />
</p>


## ما هو هذا الكود؟

هو كود يُضاف إلى ملف `functions.php` داخل قالب ووردبريس أو قالب طفل، ويقوم بتوليد **كود دعم فني فريد لكل مستخدم** في موقعك. يُستخدم هذا الكود للتحقق من ملكية الحساب عند التواصل مع الدعم الفني، مما يُعزز أمان بيانات العملاء وسهولة التحقق من الهوية.

## المزايا:

- توليد كود فريد تلقائيًا لجميع الأعضاء الحاليين والجدد.
- عرض الكود في لوحة تحكم المستخدم (WooCommerce).
- توفير صفحة داخل لوحة تحكم المسؤول تحتوي على أكواد الدعم لجميع المستخدمين.
- إمكانية عرض الكود باستخدام شورت كود. **[wppit_support_code]**
- الكود لا يحتاج إلى إضافات خارجية ويُدمج مباشرة مع القالب.
- 
## 👨‍💼 المطور

تم تطوير هذا النظام من قِبل فريق [Wppit.com](https://www.wppit.com)، المختص في حلول الأعمال الرقمية والمتاجر الإلكترونية. حتى يسهل معرفة و الإطلاع على كل حساب العميل سواء كنت تنظم اعمالك عبر نظام تذاكر دعم او جوجل شيت او crm مثل زوهو crm و الكثير . الكود الفريد يعتبر فلتر اساسى لكل حساب العميل او المورد لديك او حتى المتدرب فى المنصات التعليمية .

---

## ⚠️ ملاحظة يجب ان توضحها لمستخدمى موقعك ان الكود خاص بهم و يحتاجه موظفى الدعم لديك .

لا تقم بمشاركة كود الدعم الفني مع أي جهة خارجية، فهو بمثابة رمز ملكية لحسابك داخل المنصة

---

## طريقة الاستخدام:

1. انسخ الكود أدناه وألصقه داخل ملف `functions.php` في قالبك الحالي أو قالب child theme.
2. قم بتحديث الموقع، وسيتم توليد أكواد دعم تلقائيًا لجميع المستخدمين.
3. يمكن للمستخدمين رؤية الكود داخل صفحة حسابهم، ويمكن للمسؤولين الاطلاع عليه من لوحة التحكم.

---

## الكود الكامل:

```php
// توليد كود عشوائي لجميع الأعضاء عند تنشيط الموقع
function generate_random_code_for_all_users() {
    $args = array(
        'fields' => 'ID'
    );
    $users = get_users($args);
    foreach ($users as $user_id) {
        if (!get_user_meta($user_id, 'wppit_support_code', true)) {
            $random_code = strtoupper(uniqid('SUP-'));
            update_user_meta($user_id, 'wppit_support_code', $random_code);
        }
    }
}
add_action('init', 'generate_random_code_for_all_users');

// كود تلقائي للمستخدم الجديد
add_action('user_register', 'generate_random_code_for_new_user');
function generate_random_code_for_new_user($user_id) {
    if (!get_user_meta($user_id, 'wppit_support_code', true)) {
        $random_code = strtoupper(uniqid('SUP-'));
        update_user_meta($user_id, 'wppit_support_code', $random_code);
    }
}

// عرض الكود في حساب المستخدم
add_action('woocommerce_account_dashboard', 'display_random_code_in_account_dashboard');
function display_random_code_in_account_dashboard() {
    $user_id = get_current_user_id();
    $random_code = get_user_meta($user_id, 'wppit_support_code', true);
    if ($random_code) {
        echo '<h2>كود الدعم الفني الخاص بك:</h2>';
        echo '<p>' . esc_html($random_code) . '</p>';
        echo '<p>إستخدم هذا الكود عند التواصل مع الدعم الفنى أو الدردشة المباشرة للتحقق من ملكية حسابك</p>';
    }
}

// صفحة المسؤول لعرض الأكواد
add_action('admin_menu', 'support_code_admin_menu');
function support_code_admin_menu() {
    add_menu_page('Support Codes', 'Support Codes', 'manage_options', 'support-codes', 'display_support_codes');
}
function display_support_codes() {
    $args = array(
        'fields' => array('ID', 'user_login', 'user_email', 'user_registered')
    );
    $users = get_users($args);
    echo '<h1>كود التحقق من ملكية الحساب المخصص للدعم الفنى</h1>';
    echo '<table style="width:100%; border-collapse: collapse;">';
    echo '<thead style="background-color:#282828; color:white;">';
    echo '<tr>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">إسم المستخدم</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">البريد الإلكتروني</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">Support Code</th>';
    echo '<th style="padding: 8px; border: 1px solid #ddd;">تاريخ التسجيل</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($users as $user) {
        $support_code = get_user_meta($user->ID, 'wppit_support_code', true);
        echo '<tr style="background-color:#f9f9f9;">';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($user->user_login) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($user->user_email) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($support_code) . '</td>';
        echo '<td style="padding: 8px; border: 1px solid #ddd;">' . esc_html(date('F j, Y', strtotime($user->user_registered))) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<p style="margin-top:20px;">تم تطوير هذه الميزة بواسطة <a href="https://www.wppit.com" target="_blank">Wppit.com</a></p>';
}

// شورت كود لعرض كود الدعم
function wppit_support_code_display_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>يرجى تسجيل الدخول لعرض كود الدعم الخاص بك.</p>';
    }
    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $username = $user_info->display_name;
    $support_code = get_user_meta($user_id, 'wppit_support_code', true);
    if (!$support_code) {
        return '<p>لم يتم العثور على كود الدعم الخاص بك.</p>';
    }
    $output = '<div class="wppit-support-box">';
    $output .= '<p>👋 مرحباً <strong>' . esc_html($username) . '</strong> | كود الدعم الخاص بك هو: <strong>' . esc_html($support_code) . '</strong></p>';
    $output .= '<p style="font-size: 14px; color: #888;">ملاحظة: لا تشارك كود الدعم الفني مع الآخرين، لأنه مرتبط بملكية حسابك في</p>';
    $output .= '</div>';
    return $output;
}
add_shortcode('wppit_support_code', 'wppit_support_code_display_shortcode');

