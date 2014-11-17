import Ember from 'ember';

export default Ember.Component.extend({
  classNames: ['alert-level'],
  classNameBindings: ['colorclass'],
  colorclass: function () {
    return 'alert-level-'+this.get('level');
  }.property('level')
});
