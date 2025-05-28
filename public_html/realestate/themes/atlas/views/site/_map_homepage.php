<div class="map_full_with_homepage">
    <?php
    $this->widget(
        'application.modules.viewallonmap.components.ViewallonmapWidget',
        array(
            'selectedIds' => HApartment::getApartmentIdForHomepageMap(),
            'filterOn' => false,
            'withCluster' => ((count($selectedIds)) > 15) ? true : false,
            'scrollWheel' => false,
            'draggable' => true,
            'lazyLoadMarkers' => true,
        )
    );
    ?>
</div>