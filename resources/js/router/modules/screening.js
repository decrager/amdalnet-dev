import Layout from '@/layout';

const screeningRoutes = {
  path: '/screening',
  component: Layout,
  redirect: '/screening',
  meta: { title: 'userProfile', icon: 'component', permissions: ['view menu screening'] },
  children: [
    {
      path: '',
      component: () => import('@/views/project'),
      name: 'screeningProject',
      meta: { title: 'screeningProject', icon: 'component', permissions: ['view menu screening'] },
    },
  ],

  // path: '/screening',
  // component: Layout,
  // meta: {
  //   title: 'screening',
  //   icon: 'el-icon-reading',
  //   permissions: ['view menu screening'],
  // },
  // children: [
  //   {
  //     path: '',
  //     component: () => import('@/views/project'),
  //     name: 'screeningProject',
  //     meta: { title: 'screeningProject', icon: 'el-icon-school' },
  //   },
  // ],
};

export default screeningRoutes;
