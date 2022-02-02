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
      component: () => import('@/views/ukl-upl/FormulirUklUpl'),
      name: 'FormulirUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/matriks',
      component: () => import('@/views/ukl-upl/MatriksUklUpl'),
      name: 'MatriksUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/dokumen',
      component: () => import('@/views/ukl-upl/DokumenUklUpl'),
      name: 'DokumenUklUpl',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/pengujian',
      component: () => import('@/views/pengujian-ukl-upl/index'),
      name: 'pengujianUKLUPL',
      meta: { title: 'Pengujian', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/rekomendasi-uji-kelayakan',
      component: () =>
        import(
          '@/views/pengujian-ukl-upl/components/ujiKelayakan/suratRekomendasi'
        ),
      name: 'rekomendasiUjiKelayakanUKLUPL',
      meta: {
        title: 'Surat Rekomendasi Uji Kelayakan',
        icon: 'zip',
        noCache: true,
      },
    },
    {
      path: ':id(\\d+)/pkplh',
      component: () => import('@/views/pkplh/index'),
      name: 'pkplh',
      meta: {
        title: 'Persetujuan Pernyataan Kesanggupan Pengelolaan Lingkungan Hidup',
        icon: 'zip',
        noCache: true,
      },
    },
  ],
};

export default ukluplRoutes;
