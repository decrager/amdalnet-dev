import Layout from '@/layout';

const ukluplStaticRoutes = {
  path: '/ukluplstatic',
  component: Layout,
  meta: { title: 'UKLUPLStatic', icon: 'zip' },
  hidden: true,
  children: [
    {
      path: 'form',
      component: () => import('@/views/ukl-upl-static/Form'),
      name: 'formUKLUPL',
      meta: { title: 'formUKLUPL', icon: 'zip', noCache: true },
    },
    {
      path: 'matriks',
      component: () => import('@/views/ukl-upl-static/Matriks'),
      name: 'matriksUKLUPL',
      meta: { title: 'Matriks UKL UPL', icon: 'zip', noCache: true },
    },
    {
      path: 'doc',
      component: () => import('@/views/ukl-upl-static/Document'),
      name: 'dokumenUKLUPL',
      meta: { title: 'Dokumen UKL UPL', icon: 'zip', noCache: true },
    },
  ],
};

export default ukluplStaticRoutes;
