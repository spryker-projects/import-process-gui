/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../sass/main.scss');

var $id = $('#refresh-status-button').data('id');
$('#refresh-status-button').click(function(e) {
    e.preventDefault();
    $('#process-status-container').load('/import-process-gui/index/view-status?id-process=' + $id);
    var $newStatus = $('#process-status-data').data('status');
    if ($newStatus !== 'created') {
        $('#refresh-status-button').remove();
    }
});
