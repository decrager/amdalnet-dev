import Layout from '@/layout';

const announcementRoutes = {
  path: '/lsp-team',
  component: Layout,
  redirect: '/lsp-team',
  alwaysShow: false,
  meta: { title: 'lsp-team', icon: 'zip', roles: ['lsp', 'admin'] },
  children: [
    {
      path: ':id(\\d+)/anggota-penyusun',
      component: () => import('@/views/lsp/Member'),
      name: 'lspFormulatorMember',
      hidden: true,
      meta: {
        title: 'Daftar Penyusun LSP',
        icon: 'documentation',
        noCache: true,
        roles: ['lsp', 'admin'],
      },
    },
  ],
};

export default announcementRoutes;
