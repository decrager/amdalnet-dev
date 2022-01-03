import Layout from '@/layout';

const dokumenKegiatanRoutes = {
  path: '/dokumen-kegiatan',
  component: Layout,
  redirect: '/dokumen-kegiatan',
  meta: { title: 'Dokumen Kegiatan', icon: 'zip' },
  hidden: true,
  children: [
    {
      path: ':id(\\d+)/penyusunan-andal',
      component: () => import('@/views/penyusunan-andal/index'),
      name: 'penyusunanAndal',
      meta: { title: 'Penyusunan Andal', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/penyusunan-rkl-rpl',
      component: () => import('@/views/rkl-rpl/index'),
      name: 'penyusunanRKLRPL',
      meta: { title: 'Penyusunan RKL RPL', icon: 'zip', noCache: true },
    },
    // dummy for uklupl temp
    {
      path: ':id(\\d+)/penyusunan-rkl-rpl-dummy',
      component: () => import('@/views/rkl-rpl/index-dummy'),
      name: 'penyusunanRKLRPLDummy',
      meta: { title: 'Penyusunan RKL RPL', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/pengujian-ka',
      component: () => import('@/views/pengujian/index'),
      name: 'pengujianKA',
      meta: { title: 'Pengujian KA', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/pengujian-rkl-rpl',
      component: () => import('@/views/pengujian-rkl-rpl/index'),
      name: 'pengujianRKLRPL',
      meta: { title: 'Pengujian RKL RPL', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/rekomendasi-uji-kelayakan',
      component: () =>
        import(
          '@/views/pengujian-rkl-rpl/components/ujiKelayakan/SuratRekomendasi'
        ),
      name: 'rekomendasiUjiKelayakan',
      meta: {
        title: 'Surat Rekomendasi Uji Kelayakan',
        icon: 'zip',
        noCache: true,
      },
    },
    {
      path: ':id(\\d+)/skkl',
      component: () => import('@/views/skkl/index'),
      name: 'skkl',
      meta: {
        title: 'Surat Keputusan Kelayakan Lingkungan',
        icon: 'zip',
        noCache: true,
      },
    },
  ],
};

export default dokumenKegiatanRoutes;
