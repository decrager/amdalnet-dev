import Layout from '@/layout';

const configurationRoutes = {
  path: '/configuration',
  component: Layout,
  meta: {
    title: 'configuration',
    icon: 'resources',
    permissions: [
      'view menu configuration',
      'view menu component',
      'view menu rona awal',
      'view menu sop',
    ],
  },
  children: [
    {
      path: 'component',
      component: () => import('@/views/component/index'),
      name: 'componentActivities',
      meta: {
        title: 'componentActivities',
        icon: 'documentation',
        noCache: true,
        permissions: ['view menu component'],
      },
    },
    {
      path: 'rona-awal',
      component: () => import('@/views/rona-awal/index'),
      name: 'rona-awal',
      meta: {
        title: 'ronaAwal',
        icon: 'documentation',
        noCache: true,
        permissions: ['view menu rona awal'],
      },
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
        permissions: ['manage rona awal'],
      },
    },
    {
      path: 'sop',
      component: () => import('@/views/master-sop/index'),
      name: 'sop',
      meta: {
        title: 'sop',
        icon: 'documentation',
        noCache: true,
        permissions: ['view menu sop'],
      },
    },
    {
      path: 'sop/create',
      component: () => import('@/views/master-sop/Create'),
      name: 'createSop',
      hidden: true,
      meta: {
        title: 'addSop',
        icon: 'documentation',
        noCache: true,
        permissions: ['manage sop'],
      },
    },
    {
      path: 'cluster',
      component: () => import('@/views/error-page/404'),
      name: 'cluster',
      meta: {
        title: 'cluster',
        icon: 'documentation',
        noCache: true,
        permissions: ['view menu cluster'],
      },
    },
    {
      path: 'map-service',
      component: () => import('@/views/arcgis-service/Index'),
      name: 'Map Service',
      meta: {
        title: 'Map Service',
        icon: 'international',
        permissions: ['view menu cluster'],
      },
    },
  ],
};

export default configurationRoutes;
