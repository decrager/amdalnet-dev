import Layout from '@/layout';

const configurationRoutes = {
  path: '/configuration',
  component: Layout,
  meta: {
    title: 'configuration',
    icon: 'el-icon-reading',
    permissions: ['view menu configuration'],
  },
  children: [
    {
      path: 'component',
      component: () => import('@/views/component/index'),
      name: 'componentActivities',
      meta: { title: 'componentActivities', icon: 'documentation', noCache: true, permissions: ['view menu component'] },
    },
    {
      path: 'rona-awal',
      component: () => import('@/views/rona-awal/index'),
      name: 'rona-awal',
      meta: { title: 'ronaAwal', icon: 'documentation', noCache: true, permissions: ['view menu env params'] },
    },
    {
      path: 'rona-awal/create',
      component: () => import('@/views/lpjp/Create'),
      name: 'createRonaAwal',
      hidden: true,
      meta: {
        title: 'addRona',
        icon: 'documentation',
        noCache: true,
      },
    },
    {
      path: 'sop',
      component: () => import('@/views/master-sop/index'),
      name: 'sop',
      meta: { title: 'sop', icon: 'documentation', noCache: true, permissions: ['view menu sop'] },
    },
    {
      path: 'sop/create',
      component: () => import('@/views/master-sop/Create'),
      name: 'createSop',
      hidden: true,
      meta: { title: 'addSop', icon: 'documentation', noCache: true },
    },
    {
      path: 'cluster',
      component: () => import('@/views/error-page/404'),
      name: 'cluster',
      meta: { title: 'cluster', icon: 'documentation', noCache: true, permissions: ['view menu cluster'] },
    },
    {
      path: 'map-service',
      component: () => import('@/views/arcgis-service/Index'),
      name: 'Map Service',
      meta: { title: 'Map Service', icon: 'international', permissions: ['view menu cluster'] },
    },
  ],
};

export default configurationRoutes;
