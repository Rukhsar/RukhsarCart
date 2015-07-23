<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

$config['table_users'] = 'users';
$config['table_groups'] = 'user_groups';
$config['table_profiles'] = 'user_profiles';

$config['site_title'] = '__SITE_TITLE__';

$config['site_url'] = '__HTTP_SERVER__';

$config['absolute_path'] = '__DIR_APPLICATION__';

$config['admin_email'] = '__ADMIN_EMAIL__';

$config['default_group'] = 2;

$config['admin_group'] =  1;

$config['email_activation'] = __EMAIL_ACTIVATION__;

$config['approve_registration'] = __APPROVE_REGISTRATION__;

$config['email_activation_expire'] = 60 * 60 * 24;

$config['email_subject_1'] = 'Thank you for registering';

$config['email_subject_2'] = 'New password';

$config['email_subject_3'] = 'A new customer has registered';

$config['user_expire'] = 3600 * 24 * 30;

$config['secret_word'] = '__SECRET_WORD__';


?>
