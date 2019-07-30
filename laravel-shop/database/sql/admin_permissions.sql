INSERT INTO `admin_permissions` VALUES (1, 'All permission', '*', '', '*', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (2, 'Dashboard', 'dashboard', 'GET', '/', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (3, 'Login', 'auth.login', '', '/auth/login\r\n/auth/logout', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (4, 'User setting', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (5, 'Auth management', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (6, '用户管理', 'users', '', '/users*', '2019-7-18 07:55:47', '2019-7-18 07:55:47');
INSERT INTO `admin_permissions` VALUES (7, '商品管理', 'products', '', '/products*', '2019-7-29 17:05:44', '2019-7-29 17:05:44');
INSERT INTO `admin_permissions` VALUES (8, '优惠卷管理', 'coupon_codes', '', '/coupon_codes*', '2019-7-29 17:06:16', '2019-7-29 17:06:16');
INSERT INTO `admin_permissions` VALUES (9, '订单管理', 'orders', '', '/orders*', '2019-7-29 17:06:36', '2019-7-29 17:06:36');
