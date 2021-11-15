import Layout from '@/layout';

const feedbackRoutes = {
  path: '/feedback',
  component: Layout,
  redirect: '/feedback',
  alwaysShow: true,
  hidden: true,
  meta: { title: 'Feedback', icon: 'zip' },
  children: [
    {
      path: 'create',
      component: () => import('@/views/feedback/Create'),
      name: 'CreateFeedback',
      hidden: true,
      meta: { title: 'Buat SPT', icon: 'documentation', noCache: true },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/feedback/Edit'),
      name: 'EditFeedback',
      hidden: true,
      meta: { title: 'Edit SPT', icon: 'documentation', noCache: true },
    },
  ],
};

export default feedbackRoutes;
