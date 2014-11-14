import DS from 'ember-data';

export default DS.Model.extend({
  server:                       DS.attr('string'),
  description:                  DS.attr('string'),
  level:                        DS.attr('number'),
  full_log:                     DS.attr('string'),
  timestamp:                    DS.attr('date'),
  acknowledgement:              DS.belongsTo('acknowledgement', {async: true}),
  comments:                     DS.hasMany('comment', {async: true})
});
