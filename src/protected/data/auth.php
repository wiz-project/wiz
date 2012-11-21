<?php
return array (
  'citizen' => 
  array (
    'type' => 2,
    'description' => 'Citizen user',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewOwnProfile',
      1 => 'updateOwnProfile',
      2 => 'giveEvaluation',
      3 => 'viewEvaluation',
    ),
    'assignments' => 
    array (
      'citizen' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'planner' => 
  array (
    'type' => 2,
    'description' => 'Planner',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewOwnProfile',
      1 => 'updateOwnProfile',
      2 => 'viewNotifications',
      3 => 'createWaterRequest',
      4 => 'listWaterRequest',
      5 => 'viewOwnWaterRequest',
      6 => 'updateOwnWaterRequest',
      7 => 'restoreOwnWaterRequest',
      8 => 'saveOwnWaterRequest',
      9 => 'submitOwnWaterRequest',
      10 => 'cancelOwnWaterRequest',
      11 => 'pdfOwnWaterRequest',
      12 => 'shpOwnWaterRequest',
    ),
    'assignments' => 
    array (
      'planner' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      'test' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'wrut' => 
  array (
    'type' => 2,
    'description' => 'Water Resource Utility',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewOwnProfile',
      1 => 'updateOwnProfile',
      2 => 'viewNotifications',
      3 => 'viewWaterRequest',
      4 => 'listWaterRequest',
      5 => 'updateWaterRequest',
      6 => 'approveWaterRequest',
      7 => 'rejectWaterRequest',
      8 => 'uploadZip',
      9 => 'pdfWaterRequest',
      10 => 'epanetWaterRequest',
      11 => 'uploadEpanet',
      12 => 'shpWaterRequest',
      13 => 'viewSystemParams',
      14 => 'createSystemParam',
      15 => 'updateSystemParam',
      16 => 'loadExcel',
      17 => 'viewFault',
      18 => 'createFault',
      19 => 'updateFault',
      20 => 'viewQuality',
      21 => 'createQuality',
      22 => 'updateQuality',
    ),
    'assignments' => 
    array (
      'wrut' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'wrua' => 
  array (
    'type' => 2,
    'description' => 'Water Resource Utility',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewOwnProfile',
      1 => 'updateOwnProfile',
      2 => 'viewNotifications',
      3 => 'viewWaterRequest',
      4 => 'listWaterRequest',
      5 => 'futureWaterRequest',
      6 => 'confirmWaterRequest',
      7 => 'refuseWaterRequest',
      8 => 'uploadZip',
      9 => 'pdfWaterRequest',
      10 => 'shpWaterRequest',
    ),
    'assignments' => 
    array (
      'wrua' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'sys_admin' => 
  array (
    'type' => 2,
    'description' => 'Can perform all kind of operations',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewProfile',
      1 => 'updateProfile',
      2 => 'listProfile',
      3 => 'viewNotifications',
      4 => 'viewSystemParams',
      5 => 'createSystemParam',
      6 => 'updateSystemParam',
      7 => 'loadExcel',
      8 => 'viewFault',
      9 => 'createFault',
      10 => 'updateFault',
      11 => 'viewQuality',
      12 => 'createQuality',
      13 => 'updateQuality',
    ),
    'assignments' => 
    array (
      'sys_admin' => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'developer' => 
  array (
    'type' => 2,
    'description' => 'Developer',
    'bizRule' => '',
    'data' => '',
    'children' => 
    array (
      0 => 'viewPlugins',
      1 => 'enablePlugins',
      2 => 'disablePlugins',
      3 => 'uploadPlugins',
    ),
  ),
  'viewOwnProfile' => 
  array (
    'type' => 1,
    'description' => 'Can view own profile',
    'bizRule' => 'return Yii::app()->user->id==$params["users"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'viewProfile',
    ),
  ),
  'updateOwnProfile' => 
  array (
    'type' => 1,
    'description' => 'Can update own profile',
    'bizRule' => 'return Yii::app()->user->id==$params["users"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'updateProfile',
    ),
  ),
  'giveEvaluation' => 
  array (
    'type' => 0,
    'description' => 'Can give an evaluation of water service',
    'bizRule' => '',
    'data' => '',
  ),
  'viewEvaluation' => 
  array (
    'type' => 0,
    'description' => 'Can view posted evaluations',
    'bizRule' => '',
    'data' => '',
  ),
  'viewOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can view own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'viewWaterRequest',
    ),
  ),
  'updateOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can update own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'updateWaterRequest',
    ),
  ),
  'saveOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can update own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'saveWaterRequest',
    ),
  ),
  'restoreOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can rrestore own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'restoreWaterRequest',
    ),
  ),
  'submitOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can update own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'submitWaterRequest',
    ),
  ),
  'cancelOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can update own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'cancelWaterRequest',
    ),
  ),
  'pdfOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can update own water request',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'pdfWaterRequest',
    ),
  ),
  'shpOwnWaterRequest' => 
  array (
    'type' => 1,
    'description' => 'Can download own water request shape',
    'bizRule' => 'return Yii::app()->user->id==$params["waterRequest"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'shpWaterRequest',
    ),
  ),
  'viewOwnNotifications' => 
  array (
    'type' => 1,
    'description' => 'Can view own notifications',
    'bizRule' => 'return Yii::app()->user->id==$params["user"]->username;',
    'data' => '',
    'children' => 
    array (
      0 => 'viewNotifications',
    ),
  ),
  'viewSystemParams' => 
  array (
    'type' => 0,
    'description' => 'Can view all system params',
    'bizRule' => '',
    'data' => '',
  ),
  'createSystemParam' => 
  array (
    'type' => 0,
    'description' => 'Can create a new system param',
    'bizRule' => '',
    'data' => '',
  ),
  'updateSystemParam' => 
  array (
    'type' => 0,
    'description' => 'Can update an existing system param',
    'bizRule' => '',
    'data' => '',
  ),
  'loadExcel' => 
  array (
    'type' => 0,
    'description' => 'Can upload a .xls file',
    'bizRule' => '',
    'data' => '',
  ),
  'viewFault' => 
  array (
    'type' => 0,
    'description' => 'Can view all faults',
    'bizRule' => '',
    'data' => '',
  ),
  'createFault' => 
  array (
    'type' => 0,
    'description' => 'Can create a new fault',
    'bizRule' => '',
    'data' => '',
  ),
  'updateFault' => 
  array (
    'type' => 0,
    'description' => 'Can update an existing fault',
    'bizRule' => '',
    'data' => '',
  ),
  'viewQuality' => 
  array (
    'type' => 0,
    'description' => 'Can view all qualities',
    'bizRule' => '',
    'data' => '',
  ),
  'createQuality' => 
  array (
    'type' => 0,
    'description' => 'Can create a new quality',
    'bizRule' => '',
    'data' => '',
  ),
  'updateQuality' => 
  array (
    'type' => 0,
    'description' => 'Can update an existing quality',
    'bizRule' => '',
    'data' => '',
  ),
  'viewProfile' => 
  array (
    'type' => 0,
    'description' => 'Can view a profile',
    'bizRule' => '',
    'data' => '',
  ),
  'updateProfile' => 
  array (
    'type' => 0,
    'description' => 'Can update a profile',
    'bizRule' => '',
    'data' => '',
  ),
  'listProfile' => 
  array (
    'type' => 0,
    'description' => 'Can list all profiles',
    'bizRule' => '',
    'data' => '',
  ),
  'createWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can create a waterRequest',
    'bizRule' => '',
    'data' => '',
  ),
  'viewWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can view a waterRequest',
    'bizRule' => '',
    'data' => '',
  ),
  'updateWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can update a waterRequest',
    'bizRule' => 'return $params["waterRequest"]->isEditable();',
    'data' => '',
  ),
  'listWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can view the index',
    'bizRule' => '',
    'data' => '',
  ),
  'saveWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set water request status to saved',
    'bizRule' => '',
    'data' => '',
  ),
  'restoreWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set water request status to saved',
    'bizRule' => '',
    'data' => '',
  ),
  'cancelWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set cancelled status',
    'bizRule' => '',
    'data' => '',
  ),
  'submitWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set submitted status',
    'bizRule' => '',
    'data' => '',
  ),
  'rejectWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set rejected status',
    'bizRule' => 'return (($params["waterRequest"]->phase==2) || ($params["waterRequest"]->phase==3));',
    'data' => '',
  ),
  'approveWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set approved status',
    'bizRule' => 'return (($params["waterRequest"]->phase==2) || ($params["waterRequest"]->phase==3));',
    'data' => '',
  ),
  'futureWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set in_future status',
    'bizRule' => '',
    'data' => '',
  ),
  'confirmWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set confirmed status',
    'bizRule' => 'return (($params["waterRequest"]->phase==2) || ($params["waterRequest"]->phase==3));',
    'data' => '',
  ),
  'refuseWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can set refused status',
    'bizRule' => 'return (($params["waterRequest"]->phase==2) || ($params["waterRequest"]->phase==3));',
    'data' => '',
  ),
  'pdfWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can generate waterRequest pdf',
    'bizRule' => '',
    'data' => '',
  ),
  'epanetWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can generate waterRequests epanet file',
    'bizRule' => 'return $params["waterRequest"]->phase==3;',
    'data' => '',
  ),
  'uploadEpanet' => 
  array (
    'type' => 0,
    'description' => 'Can upload an inp file',
    'bizRule' => '',
    'data' => '',
  ),
  'uploadZip' => 
  array (
    'type' => 0,
    'description' => 'Can upload an archive',
    'bizRule' => '',
    'data' => '',
  ),
  'shpWaterRequest' => 
  array (
    'type' => 0,
    'description' => 'Can download all waterRequests shapefile',
    'bizRule' => '',
    'data' => '',
  ),
  'viewNotifications' => 
  array (
    'type' => 0,
    'description' => 'Can view a notification',
    'bizRule' => '',
    'data' => '',
  ),
  'viewPlugins' => 
  array (
    'type' => 0,
    'description' => 'Can view plugins list',
    'bizRule' => '',
    'data' => '',
  ),
  'enablePlugins' => 
  array (
    'type' => 0,
    'description' => 'Can enable a plugin',
    'bizRule' => '',
    'data' => '',
  ),
  'disablePlugins' => 
  array (
    'type' => 0,
    'description' => 'Can disable a plugin',
    'bizRule' => '',
    'data' => '',
  ),
  'uploadPlugins' => 
  array (
    'type' => 0,
    'description' => 'Can upload a plugin',
    'bizRule' => '',
    'data' => '',
  ),
);
