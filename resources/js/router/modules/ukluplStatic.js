import Layout from '@/layout';

const ukluplStaticRoutes = {
  path: '/ukluplstatic/form',
  component: Layout,
  meta: { title: 'UKLUPLStatic', icon: 'zip' },
  hidden: true,
  children: [
    {
      path: '',
      component: () => import('@/views/ukl-upl-static/Form'),
      name: 'formUKLUPL',
      meta: { title: 'formUKLUPL', icon: 'zip', noCache: true },
    },
  ],
};

export default ukluplStaticRoutes;
