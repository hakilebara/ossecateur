import Ember from 'ember';

export default Ember.Component.extend({
  fixHeader: function() {
    var $table     = this.$(),
        $bodyCells = $table.find('tbody tr:first').children(),
        colWidth;

    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();    

    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });

  }.on('didInsertElement'),
});
