import Ember from 'ember';

export default Ember.Component.extend({
  attributeBindings: ['tabindex'],
  tabindex: 0,
  focusOut: function() {
    //this.send('toggleAckDropdownActive');
    this.set('isAckDropdownActive', false);
  },
  isAckDropdownActive: false,
  actions: {
    toggleAckDropdownActive: function() {
      //this.toggleProperty('isAckDropdownActive');
      this.set('isAckDropdownActive', true);
    },
    selectValue: function(value) {
      this.sendAction('selectedValue', value);
      this.set('isAckDropdownActive', false);
      //this.set('tabindex', -1);
      //console.log($().attr('id'));
      //console.log(this.get('element').attr('id'));
      //$().focusin();
      //this.get('element').focusout();
    }
  },
});

