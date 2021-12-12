import Layout from '@/layout';

const ukluplRoutes = {
  path: '/uklupl',
  component: Layout,
  redirect: '/uklupl',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'UKL UPL', icon: 'zip' },
  children: [
    {
      path: ':id(\\d+)/formulir',
      component: () => import('@/views/ukl-upl-dummy/FormulirUklUpl'),
      name: 'FormulirUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/matriks',
      component: () => import('@/views/ukl-upl-dummy/MatriksUklUpl'),
      name: 'MatriksUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/dokumen',
      component: () => import('@/views/ukl-upl-dummy/DokumenUklUpl'),
      name: 'DokumenUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
  ],
};

export default ukluplRoutes;
