import Ember from 'ember';

var InputFocusComponent = Ember.TextField.extend({
  becomeFocused: function() {
      this.$().animate({width:'+=400px'},200, 'linear');
      this.$().focus();
  }.on('didInsertElement')
});

export default InputFocusComponent;
