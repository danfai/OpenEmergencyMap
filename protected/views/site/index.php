<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$baseUrl = Yii::app()->baseUrl . '/static/';

?>
<div id="sidebar">
    <div id="insert-form">
        <form method="POST" onsubmit="return insertSubmit(event);">
            <div class="form">
                <div class="row">
                    <label for="name">Name: </label>
                    <input type="text" name="name" id="input-name" />
                </div>
                <div class="row">
                    <label for="descr">Beschreibung: </label>
                    <input type="text" name="descr" id="input-descr" /><br />
                </div>
                <fieldset>
                    <legend>Attribute:</legend>
                    <span>Hier sollen dann zusätzliche Informationen rein. Ist die Frage, wie es aussehen soll...</span>
                </fieldset>
                <input type="submit" id="insert-submit" value="Absenden"/>
            </div>
        </form>
    </div>
</div>
<div id="startDialog" title="Hilfe oder Helfer?" style="display: none">
    <p>Suchst du <b>Hilfe</b> oder m&ouml;chtest du dich als <b>Helfer</b> zur Verf&uuml;gung stellen.</p>
    <table>
        <tr style="height: 50px">
            <td style="vertical-align: top">
                <h4>Hilfe!</h4>
                <p>Schickt mir Helfer!</p>
            </td>
            <td class="clickable" style="vertical-align:top;width:50%" onclick="locateUser()">
                <h4>Helfer</h4>
                <p>Wo kann ich in meiner N&auml;he helfen? (Ortung)</p>
            </td>
        </tr>
    </table>
</div>
<script>
    $("#startDialog").dialog({closable: true});

    L.Icon.Default.imagePath = "<?php echo $baseUrl ?>images";
    var tile = L.tileLayer('/tiles/{z}/{x}/{y}.png');
    var overlayItems = L.featureGroup();
    var drawnItems = L.featureGroup();
    var map = L.map('content',{layers:[tile,overlayItems,drawnItems]});
    var hash = L.hash(map);
    if(!hash.lastHash){
        map.setView([49.97,8.5],11);
    }
    overlayItems.bindPopup(L.popup());
    var tmpLayer = {};
    var sidebar = L.control.sidebar('sidebar');
    sidebar.addTo(map);
    sidebar.on('hide',function(){
        edit.disable();
        drawnItems.clearLayers();
        overlayItems.addLayer(tmpLayer.layer);
        tmpLayer = {};
    });
    var edit = (new L.EditToolbar.Edit(map,{
        featureGroup:drawnItems,
        selectedPathOptions:L.EditToolbar.prototype.options.edit.selectedPathOptions
    }));

    overlayItems.on('click',function(e){
        $.post('<?php echo $this->createUrl('object/details'); ?>',{
            'id': e.layer.object_id
        },function(data){
            e.layer._popup.setContent("<b>" + data.name + "</b><br />" + data.description
                + "<br /><a href='#' class='edit'>Edit</a> <a href='#' class='delete'>Delete</a>");

            $('.edit').click(function(event){
                event.preventDefault();
                e.layer.closePopup();setView([49.97,8.5],11)
                overlayItems.removeLayer(e.layer);
                drawnItems.addLayer(e.layer);
                tmpLayer.layer = e.layer;
                tmpLayer.layerType = e.layer.type;
                edit.enable();
                $("#insert-form").show();
                $('#insert-form #input-name').val(data.name);
                $('#insert-form #input-descr').val(data.description);
                sidebar.show();
            });
            $('.delete').click(function(){
                $.post('<?php echo $this->createUrl('object/delete'); ?>',{id: e.layer.object_id},function(data){
                    loadOverlay();
                },'json');
            });
        },'json');
    });

    //TODO: Leaflet Clustering Marker einbinden

    map.on('autopanstart',function(){
        overlayItems.autoPanningActive = true;
    });
    map.on('moveend',loadOverlay);

    function loadOverlay(){
        if(overlayItems.autoPanningActive) {
            window.setTimeout(function(){
                overlayItems.autoPanningActive = false;
            },500);
            return;
        }
        if(drawnItems.getLayers().length > 0)
            return;
        //TODO: Zu viel overhead
        $.post('<?php echo $this->createUrl('object/receive') ?>',{
            'bbox': map.getBounds().toBBoxString()
        },function(data){
            overlayItems.clearLayers();
            /*if(data.moreObjectsAvailable) {
                notifyUser("Es sind mehr Objekte vorhanden, bitte zoome hinein, um alle Objekte zu sehen.","error");
            } */
            $.each(data,function(i,elem){
                var layer;
                switch(elem.type){
                    case 'marker':
                        layer = L.marker(elem.coordinates[0],{'title':elem.name});
                        break;
                    case 'rectangle':
                    case 'polygon':
                        layer = L.polygon(elem.coordinates);
                        break;
                    case 'polyline':
                        layer = L.polyline(elem.coordinates);
                        break;
                    case 'circle':
                        console.log("Circle is currently not supported");
                        layer = L.circle(elem.coordinates[0]);
                        break;
                }
                layer.object_id = elem.id;
                layer.type = elem.type;
                overlayItems.addLayer(layer);
            });
        },'json');
    }

    L.control.layers({"Karte" : tile},{"Overlay":overlayItems},{collapsed:false}).addTo(map);

    var drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            'rectangle': false,
            'circle': false,
            polygon: {
                allowIntersection: false
            }
        },
        edit: false,
        delete: false
    });

    map.addControl(drawControl);

    L.Control.Help = L.Control.extend({
        options: {
            position: 'topright'
        },

        onAdd: function(map) {
            var container = L.DomUtil.create('div', 'leaflet-control-help leaflet-bar');

            this._createButton('?', 'Help', 'help', container, function(){
                $("#startDialog").dialog();
            });

            return container;
        },

        // Copied from leaflet
        _createButton: function (html, title, className, container, fn, context) {
            var link = L.DomUtil.create('a', className, container);
            link.innerHTML = html;
            link.href = '#';
            link.title = title;

            var stop = L.DomEvent.stopPropagation;

            L.DomEvent
                .on(link, 'click', stop)
                .on(link, 'mousedown', stop)
                .on(link, 'dblclick', stop)
                .on(link, 'click', L.DomEvent.preventDefault)
                .on(link, 'click', fn, context)
                .on(link, 'click', this._refocusOnMap, context);

            return link;
        }
    });

    map.addControl(new L.Control.Help);

    loadOverlay();
    map.on('draw:created',function(e){
        tmpLayer = e;
        drawnItems.addLayer(e.layer);
        sidebar.show();
    });
    map.on('draw:drawstart',function(e){
        drawnItems.clearLayers();
    });
    function getLatLng(latlng){
        return {
            'lat':latlng.lat,
            'lng':latlng.lng
        };
    }

    function getCoordinates(layer, layerType){
        var result = [];
        switch(layerType){
            case 'marker':
                result = [getLatLng(layer.getLatLng())];
                break;
            case 'circle':
                console.log("Circle is currently not supported");
                result= [getLatLng(layer.getLatLng())];
                break;
            case 'polygon':
            case 'rectangle':
            case 'polyline':
                tmp = layer.getLatLngs();
                $.each(tmp,function(i,elem){
                    tmp[i] = getLatLng(tmp[i]);
                });
                result = tmp;
                break;
        }
        return result;
    }

    function insertSubmit(e){
        e.preventDefault();
        var url;
        var object = {
            'coordinates': getCoordinates(tmpLayer.layer,tmpLayer.layerType),
            'type': tmpLayer.layerType,
            'name': $(e.target).find('input#input-name').val(),
            'description': $(e.target).find('input#input-descr').val()
        };
        if(edit.enabled()) {
            url = "<?php echo $this->createUrl('object/edit') ?>";
            object.id = tmpLayer.layer.object_id;
        } else {
            url = "<?php echo $this->createUrl('object/create') ?>";
        }
        $.post(url, object,function(data){
            drawnItems.clearLayers();
            sidebar.hide();
            loadOverlay();
            if(edit.enabled()) {
                edit.disable();
            }
        },'json');
        return false;
    }

    function locateUser(){
        map.locate({setView:true});
        map.on('locationerror',function(){
            notifyUser('Position not found', 'error');
        });
        map.on('locationfound',function(e){
            console.log(e);
            notifyUser('Position: ' + e.latlng, 'error');
        });
    }
</script>