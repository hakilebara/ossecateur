import DS from 'ember-data';

export default DS.Model.extend({
  owner:             DS.attr('string'),
  timestamp:          DS.attr('date'),
  alert:              DS.belongsTo('alert', {async: true}),
});
