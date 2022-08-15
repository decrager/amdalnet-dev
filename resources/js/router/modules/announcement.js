import Layout from '@/layout';

const announcementRoutes = {
  path: '/announcement',
  component: Layout,
  redirect: '/announcement',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'Announcement', icon: 'zip' },
  children: [
    // {
    //   path: '',
    //   component: () => import('@/views/announcement'),
    //   name: 'listAnnouncement',
    //   meta: { title: 'listAnnouncement', icon: 'documentation' },
    // },
    {
      path: 'view/:id(\\d+)',
      component: () => import('@/views/announcement/View'),
      name: 'ViewAnnouncement',
      hidden: true,
      meta: {
        title: 'Daftar Kegiatan',
        icon: 'documentation',
        permissions: [
          'view menu public consultations',
          'manage consultation',
          'do public consultation',
        ],
      },
    },
  ],
};

export default announcementRoutes;
