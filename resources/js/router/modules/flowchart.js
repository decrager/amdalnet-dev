import Layout from '@/layout';

const flowchartRoutes = {
  path: '/flowchart',
  component: Layout,
  redirect: '/flowchart',
  hidden: true,
  meta: { title: 'Workspace', icon: 'layout' },
  children: [
    {
      path: '',
      component: () => import('@/views/flowchart'),
      name: 'flowchart',
      hidden: true,
      meta: { title: 'Bagan Alir', icon: 'edit', noCache: true },
    },
  ],
};

export default flowchartRoutes;
