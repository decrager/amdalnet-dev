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
      meta: {
        title: 'Asistensi Pelingkupan',
        icon: 'documentation',
        permissions: ['view menu ukl upl form', 'do UKL/UPL review'],
      },
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
      meta: {
        title: 'Asistensi Pelingkupan',
        icon: 'documentation',
        permissions: ['view menu ukl upl document'],
      },
    },
    {
      path: ':id(\\d+)/uji-berkas-administrasi',
      component: () => import('@/views/pengujian-ukl-upl/index'),
      name: 'ujiBerkasAdministrasiUKLUPL',
      meta: {
        title: 'Uji Berkas Administrasi',
        icon: 'zip',
        noCache: true,
        permissions: [
          'view menu ukl upl adm',
          'view menu ukl upl meeting invitation',
        ],
      },
    },
    {
      path: ':id(\\d+)/berita-acara',
      component: () =>
        // import('@/views/pengujian-ukl-upl/components/beritaAcara/index'),
        import('@/views/pengujian-ukl-upl/components/beritaAcara/BeritaAcara'),
      name: 'beritaAcaraUKLUPL',
      meta: {
        title: 'Berita Acara',
        icon: 'zip',
        noCache: true,
        permissions: ['view menu ukl upl meeting report'],
      },
    },
    {
      path: ':id(\\d+)/uji-kelayakan',
      component: () =>
        import('@/views/pengujian-rkl-rpl/components/ujiKelayakan/index'),
      name: 'ujiKelayakanUKLUPL',
      meta: {
        title: 'Uji Kelayakan',
        icon: 'zip',
        noCache: true,
        permissions: ['view menu ukl upl feasibility test'],
      },
    },
    {
      path: ':id(\\d+)/rekomendasi-uji-kelayakan',
      component: () =>
        import('@/views/pengujian-ukl-upl/components/rekomendasi/index'),
      name: 'rekomendasiUjiKelayakanUKLUPL',
      meta: {
        title: 'Surat Rekomendasi Uji Kelayakan',
        icon: 'zip',
        noCache: true,
        permissions: ['view menu ukl upl feasibility test recommendation'],
      },
    },
    {
      path: ':id(\\d+)/pkplh',
      component: () => import('@/views/pkplh/index'),
      name: 'pkplh',
      meta: {
        title:
          'Persetujuan Pernyataan Kesanggupan Pengelolaan Lingkungan Hidup',
        icon: 'zip',
        noCache: true,
        permissions: ['view menu pkplh'],
      },
    },
  ],
};

export default ukluplRoutes;
