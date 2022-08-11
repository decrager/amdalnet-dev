import Layout from '@/layout';

const businessRoutes = {
  path: '/business',
  component: Layout,
  redirect: '/business',
  meta: {
    title: 'masterKbli',
    icon: 'zip',
    permissions: ['view menu params & kbli', 'manage params'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/business/index'),
      name: 'ListBusiness',
      meta: {
        title: 'masterKbli',
        icon: 'documentation',
        permissions: ['view menu params & kbli', 'manage params'],
      },
    },
    {
      path: ':id(\\d+)',
      component: () => import('@/views/business/param/index'),
      name: 'ListBusinessEnvParam',
      hidden: true,
      meta: {
        title: 'masterParameterKbli',
        icon: 'documentation',
        permissions: ['view menu params & kbli', 'manage params'],
      },
    },
  ],
};

export default businessRoutes;
