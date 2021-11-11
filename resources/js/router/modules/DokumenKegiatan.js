import Layout from '@/layout';

const dokumenKegiatanRoutes = {
  path: '/dokumen-kegiatan',
  component: Layout,
  redirect: '/dokumen-kegiatan',
  meta: { title: 'Dokumen Kegiatan', icon: 'zip' },
  children: [
    {
      path: 'penyusunan-andal',
      component: () => import('@/views/penyusunan-andal/index'),
      name: 'penyusunanAndal',
      meta: { title: 'Penyusunan Andal', icon: 'zip', noCache: true },
    },
    // {
    //   path: 'penyusunan-rkl-rpl',
    //   component: () => import('@/views/rkl-rpl/index'),
    //   name: 'penyusunanRKLRPL',
    //   meta: { title: 'Penyusuan RKL RPL', icon: 'zip', noCache: true },
    // },
    // {
    //   path: 'pengujian',
    //   component: () => import('@/views/pengujian/index'),
    //   name: 'pengujian',
    //   meta: { title: 'Pengujian', icon: 'zip', noCache: true },
    // },
  ],
};

export default dokumenKegiatanRoutes;
