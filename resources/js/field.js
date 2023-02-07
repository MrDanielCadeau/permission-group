import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-permission-group', IndexField)
  app.component('detail-permission-group', DetailField)
  app.component('form-permission-group', FormField)
})
