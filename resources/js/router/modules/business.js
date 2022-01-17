import Layout from '@/layout';

const adminRoles = [
  'admin',
  'admin-standard',
  // 'admin-system',
  // 'admin-regional',
  // 'admin-central',
];

const businessRoutes = {
  path: '/business',
  component: Layout,
  redirect: '/business',
  meta: { title: 'masterKbli', icon: 'zip', roles: adminRoles },
  children: [
    {
      path: '',
      component: () => import('@/views/business/index'),
      name: 'ListBusiness',
      meta: { title: 'masterKbli', icon: 'documentation', roles: adminRoles },
    },
    {
      path: ':id(\\d+)',
      component: () => import('@/views/business/param/index'),
      name: 'ListBusinessEnvParam',
      hidden: true,
      meta: { title: 'masterParameterKbli', icon: 'documentation', roles: adminRoles },
    },
  ],
};

export default businessRoutes;
