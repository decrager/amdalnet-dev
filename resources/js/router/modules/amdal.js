import Layout from '@/layout';

const amdalRoutes = {
  path: '/amdal',
  component: Layout,
  redirect: '/amdal',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'AMDAL', icon: 'zip' },
  children: [
    {
      path: ':id(\\d+)/formulir',
      component: () => import('@/views/amdal/FormulirAmdal'),
      name: 'FormulirAmdal',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/matriks',
      component: () => import('@/views/amdal/MatriksUklUpl'),
      name: 'MatriksAmdal',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
    {
      path: ':id(\\d+)/dokumen',
      component: () => import('@/views/amdal/DokumenAmdal'),
      name: 'DokumenAmdal',
      hidden: false,
      meta: { title: 'Asistensi Pelingkupan', icon: 'documentation' },
    },
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
    {
      path: ':id(\\d+)/dokumen-andal-rkl-rpl',
      component: () => import('@/views/rkl-rpl/components/Dokumen'),
      name: 'dokumenANDALRKLRPL',
      meta: { title: 'Dokumen ANDAL RKL RPL', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/uji-berkas-administrasi-ka',
      component: () => import('@/views/pengujian/index'),
      name: 'ujiBerkasAdministrasiKA',
      meta: { title: 'Uji Berkas Administrasi KA', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/berita-acara-ka',
      component: () => import('@/views/pengujian/components/beritaAcara/index'),
      name: 'beritaAcaraKA',
      meta: { title: 'Berita Acara KA', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/uji-berkas-administrasi-andal-rkl-rpl',
      component: () => import('@/views/pengujian-rkl-rpl/index'),
      name: 'ujiBerkasAdministrasiANDALRKLRPL',
      meta: { title: 'Uji Berkas Administrasi Andal RKL RPL', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/berita-acara-andal-rkl-rpl',
      component: () => import('@/views/pengujian-rkl-rpl/components/beritaAcara/index'),
      name: 'beritaAcaraANDALRKLRPL',
      meta: { title: 'Berita Acara Andal RKL RPL', icon: 'zip', noCache: true },
    },
    {
      path: ':id(\\d+)/uji-kelayakan',
      component: () => import('@/views/pengujian-rkl-rpl/components/ujiKelayakan/index'),
      name: 'ujiKelayakanAmdal',
      meta: { title: 'Uji Kelayakan', icon: 'zip', noCache: true },
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

export default amdalRoutes;
