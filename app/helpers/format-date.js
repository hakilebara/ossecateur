/* global moment:true */
import Ember from 'ember';

function formatDate(value, format) {
  return moment(value).format(format);
}

export {
  formatDate
};

export default Ember.Handlebars.makeBoundHelper(formatDate);
