<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
function autoloade4cb8374bd3dcbb0d59a468d119bbd08($class) {
    static $classes = null;
    if ($classes === null) {
        $classes = array(
            'cardcontrollerbuilderrequestdataexception' => '/CardControllerBuilderRequestDataException.class.php',
            'cardcontrollerbuilderrequestidexception' => '/CardControllerBuilderRequestIdException.class.php',
            'cardcontrollerbuilderrequestplanningidexception' => '/CardControllerBuilderRequestPlanningIdException.class.php',
            'cardwall_artifactnodetreeprovider' => '/ArtifactNodeTreeProvider.class.php',
            'cardwall_board' => '/Board.class.php',
            'cardwall_boardfactory' => '/BoardFactory.class.php',
            'cardwall_boardpresenter' => '/BoardPresenter.class.php',
            'cardwall_cardcontroller' => '/CardController.class.php',
            'cardwall_cardcontrollerbuilder' => '/CardControllerBuilder.class.php',
            'cardwall_cardfieldpresenter' => '/CardFieldPresenter.class.php',
            'cardwall_cardincellpresenter' => '/CardInCellPresenter.class.php',
            'cardwall_cardincellpresentercallback' => '/CardInCellPresenterCallback.class.php',
            'cardwall_cardincellpresenterfactory' => '/CardInCellPresenterFactory.class.php',
            'cardwall_cardincellpresenternode' => '/CardInCellPresenterNode.class.php',
            'cardwall_cardpresenter' => '/CardPresenter.class.php',
            'cardwall_column' => '/Column.class.php',
            'cardwall_createcardpresentercallback' => '/CreateCardPresenterCallback.class.php',
            'cardwall_fieldproviders_customfieldretriever' => '/FieldProviders/CustomFieldProvider.class.php',
            'cardwall_fieldproviders_iprovidefieldgivenanartifact' => '/FieldProviders/IProvideFieldGivenAnArtifact.class.php',
            'cardwall_fieldproviders_semanticstatusfieldretriever' => '/FieldProviders/SemanticStatusFieldProvider.class.php',
            'cardwall_fieldsextractor' => '/FieldsExtractor.class.php',
            'cardwall_form' => '/Form.class.php',
            'cardwall_mapping' => '/Mapping.class.php',
            'cardwall_mappingcollection' => '/MappingCollection.class.php',
            'cardwall_ontop_columndao' => '/OnTop/ColumnDao.class.php',
            'cardwall_ontop_columnmappingfielddao' => '/OnTop/ColumnMappingFieldDao.class.php',
            'cardwall_ontop_columnmappingfieldvaluedao' => '/OnTop/ColumnMappingFieldValueDao.class.php',
            'cardwall_ontop_config' => '/OnTop/Config.class.php',
            'cardwall_ontop_config_columncollection' => '/OnTop/Config/ColumnCollection.class.php',
            'cardwall_ontop_config_columnfactory' => '/OnTop/Config/ColumnFactory.class.php',
            'cardwall_ontop_config_columnfreestylecollection' => '/OnTop/Config/ColumnFreestyleCollection.class.php',
            'cardwall_ontop_config_columnstatuscollection' => '/OnTop/Config/ColumnStatusCollection.class.php',
            'cardwall_ontop_config_columnsvisitor' => '/OnTop/Config/ColumnsVisitor.class.php',
            'cardwall_ontop_config_command' => '/OnTop/Config/Command.class.php',
            'cardwall_ontop_config_command_createcolumn' => '/OnTop/Config/Command/CreateColumn.class.php',
            'cardwall_ontop_config_command_createmappingfield' => '/OnTop/Config/Command/CreateMappingField.class.php',
            'cardwall_ontop_config_command_deletecolumns' => '/OnTop/Config/Command/DeleteColumns.class.php',
            'cardwall_ontop_config_command_deletemappingfields' => '/OnTop/Config/Command/DeleteMappingFields.class.php',
            'cardwall_ontop_config_command_enablecardwallontop' => '/OnTop/Config/Command/EnableCardwallOnTop.class.php',
            'cardwall_ontop_config_command_enablefreestylecolumns' => '/OnTop/Config/Command/EnableFreestyleColumns.class.php',
            'cardwall_ontop_config_command_updatecolumns' => '/OnTop/Config/Command/UpdateColumns.class.php',
            'cardwall_ontop_config_command_updatemappingfields' => '/OnTop/Config/Command/UpdateMappingFields.class.php',
            'cardwall_ontop_config_mappedfieldprovider' => '/OnTop/Config/MappedFieldProvider.class.php',
            'cardwall_ontop_config_trackermapping' => '/OnTop/Config/TrackerMapping.class.php',
            'cardwall_ontop_config_trackermappingfactory' => '/OnTop/Config/TrackerMappingFactory.class.php',
            'cardwall_ontop_config_trackermappingfield' => '/OnTop/Config/TrackerMappingField.class.php',
            'cardwall_ontop_config_trackermappingfreestyle' => '/OnTop/Config/TrackerMappingFreestyle.class.php',
            'cardwall_ontop_config_trackermappingnofield' => '/OnTop/Config/TrackerMappingNoField.class.php',
            'cardwall_ontop_config_trackermappingstatus' => '/OnTop/Config/TrackerMappingStatus.class.php',
            'cardwall_ontop_config_updater' => '/OnTop/Config/Updater.class.php',
            'cardwall_ontop_config_valuemapping' => '/OnTop/Config/ValueMapping.class.php',
            'cardwall_ontop_config_valuemappingfactory' => '/OnTop/Config/ValueMappingFactory.class.php',
            'cardwall_ontop_config_view_columndefinition' => '/OnTop/Config/View/ColumnDefinition.class.php',
            'cardwall_ontop_config_view_freestylecolumndefinition' => '/OnTop/Config/View/FreestyleColumnDefinition.class.php',
            'cardwall_ontop_config_view_semanticstatuscolumndefinition' => '/OnTop/Config/View/SemanticStatusColumnDefinition.class.php',
            'cardwall_ontop_configempty' => '/OnTop/ConfigEmpty.class.php',
            'cardwall_ontop_configfactory' => '/OnTop/ConfigFactory.class.php',
            'cardwall_ontop_dao' => '/OnTop/Dao.class.php',
            'cardwall_ontop_iconfig' => '/OnTop/IConfig.class.php',
            'cardwall_pane' => '/Pane.class.php',
            'cardwall_panecontentpresenter' => '/PaneContentPresenter.class.php',
            'cardwall_paneinfo' => '/PaneInfo.class.php',
            'cardwall_qrcode' => '/QrCode.class.php',
            'cardwall_renderer' => '/Cardwall_Renderer.class.php',
            'cardwall_rendererdao' => '/Cardwall_RendererDao.class.php',
            'cardwall_rendererpresenter' => '/RendererPresenter.class.php',
            'cardwall_swimline' => '/Swimline.class.php',
            'cardwall_swimlinefactory' => '/SwimlineFactory.class.php',
            'cardwall_userpreferences_autostack_autostackdashboard' => '/UserPreferences/Autostack/AutostackDashboard.class.php',
            'cardwall_userpreferences_autostack_autostackrenderer' => '/UserPreferences/Autostack/AutostackRenderer.class.php',
            'cardwall_userpreferences_userpreferencesautostack' => '/UserPreferences/UserPreferencesAutostack.class.php',
            'cardwall_userpreferences_userpreferencesautostackfactory' => '/UserPreferences/UserPreferencesAutostackFactory.class.php',
            'cardwall_userpreferences_userpreferencescontroller' => '/UserPreferences/UserPreferencesController.class.php',
            'cardwall_userpreferences_userpreferencesdisplayuser' => '/UserPreferences/UserPreferencesDisplayUser.class.php',
            'cardwall_view' => '/View.class.php',
            'cardwall_view_admin' => '/View/Admin.class.php',
            'cardwall_view_admin_form' => '/View/Admin/Form.class.php',
            'cardwallconfigxmlexport' => '/CardwallConfigXmlExport.class.php',
            'cardwallconfigxmlexportnodenotvalidexception' => '/CardwallConfigXmlExportNodeNotValidException.class.php',
            'cardwallconfigxmlimport' => '/CardwallConfigXmlImport.class.php',
            'cardwallfromxmlimportcannotbeenabledexception' => '/CardwallFromXmlImportCannotBeEnabledException.class.php',
            'cardwallfromxmlinputnotwellformedexception' => '/CardwallFromXmlInputNotWellFormedException.class.php',
            'cardwallplugin' => '/cardwallPlugin.class.php',
            'cardwallplugindescriptor' => '/CardwallPluginDescriptor.class.php',
            'cardwallplugininfo' => '/CardwallPluginInfo.class.php'
        );
    }
    $cn = strtolower($class);
    if (isset($classes[$cn])) {
        require dirname(__FILE__) . $classes[$cn];
    }
}
spl_autoload_register('autoloade4cb8374bd3dcbb0d59a468d119bbd08');
// @codeCoverageIgnoreEnd