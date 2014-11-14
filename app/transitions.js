export default function() {
  this.transition(
    this.toModel(true),
    this.use('fade', {duration: 400}),
    this.reverse('toLeft', {duration: 1000})
  );
}

