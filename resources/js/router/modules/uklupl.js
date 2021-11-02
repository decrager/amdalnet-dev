import Layout from '@/layout';

const uklUplRoutes = {
  path: '/ukl-upl',
  component: Layout,
  redirect: '/ukl-upl',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'UKL-UPL', icon: 'zip' },
  children: [
    {
      path: '',
      component: () => import('@/views/ukl-upl'),
      name: 'UklUpl',
      hidden: false,
      meta: { title: 'UKL-UPL', icon: 'documentation' },
    },
  ],
};

export default uklUplRoutes;
