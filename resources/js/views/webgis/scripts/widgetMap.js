import Home from '@arcgis/core/widgets/Home';
import Compass from '@arcgis/core/widgets/Compass';
import BasemapToggle from '@arcgis/core/widgets/BasemapToggle';
import Attribution from '@arcgis/core/widgets/Attribution';
import Expand from '@arcgis/core/widgets/Expand';
import Print from '@arcgis/core/widgets/Print';
import ScaleBar from '@arcgis/core/widgets/ScaleBar';

function widgetMap(mapView) {
  const home = new Home({
    view: mapView,
  });

  mapView.popup.on('trigger-action', (event) => {
    if (event.action.id === 'get-details') {
      console.log('tbd');
    }
  });

  mapView.ui.add(home, 'top-left');
  const compass = new Compass({
    view: mapView,
  });
  mapView.ui.add(compass, 'top-left');
  const basemapToggle = new BasemapToggle({
    view: mapView,
    container: document.createElement('div'),
    secondBasemap: 'satellite',
  });
  const expandBasemapToggler = new Expand({
    view: mapView,
    name: 'basemap',
    content: basemapToggle.domNode,
    expandIconClass: 'esri-icon-basemap',
  });
  mapView.ui.add(expandBasemapToggler, 'top-left');
  const attribution = new Attribution({
    view: mapView,
  });
  mapView.ui.add(attribution, 'manual');

  const print = new Print({
    view: mapView,
    printServiceUrl:
              'https://utility.arcgisonline.com/arcgis/rest/services/Utilities/PrintingTools/GPServer/Export%20Web%20Map%20Task',
  });

  const printExpand = new Expand({
    view: mapView,
    content: print,
  });

  const scaleBar = new ScaleBar({
    view: mapView,
    unit: 'metric', // The scale bar displays both metric and non-metric units.
  });

  mapView.ui.add(scaleBar, {
    position: 'bottom-left',
  });

  mapView.ui.add(printExpand, 'top-left');
}

export default widgetMap;
