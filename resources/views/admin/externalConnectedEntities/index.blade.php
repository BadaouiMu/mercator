@extends('layouts.admin')
@section('content')
@can('external_connected_entity_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.external-connected-entities.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.externalConnectedEntity.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.externalConnectedEntity.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ExternalConnectedEntity">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.entity') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.contacts') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.network') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.src') }}
                        </th>
                        <th>
                            {{ trans('cruds.externalConnectedEntity.fields.dest') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($externalConnectedEntities as $key => $externalConnectedEntity)
                        <tr data-entry-id="{{ $externalConnectedEntity->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.external-connected-entities.show', $externalConnectedEntity->id) }}">
                                {{ $externalConnectedEntity->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $externalConnectedEntity->type ?? '' }}
                            </td>
                            <td>
                                @if ($externalConnectedEntity->entity!=null)
                                    <a href="{{ route('admin.entities.show', $externalConnectedEntity->entity->id) }}">
                                        {{ $externalConnectedEntity->entity->name }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $externalConnectedEntity->contacts }}
                            </td>
                            <td>
                                @if($externalConnectedEntity->network!=null)
                                    <a href="{{ route('admin.networks.show', $externalConnectedEntity->network->id) }}">
                                        {{ $externalConnectedEntity->network->name }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                    {{ $externalConnectedEntity->src_desc }}
                                    <br>
                                    {{ $externalConnectedEntity->src }}
                            </td>
                            <td>
                                    {{ $externalConnectedEntity->dest_desc }}
                                    <br>
                                    {{ $externalConnectedEntity->dest }}
                            </td>
                            <td>
                                @can('external_connected_entity_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.external-connected-entities.show', $externalConnectedEntity->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('external_connected_entity_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.external-connected-entities.edit', $externalConnectedEntity->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('external_connected_entity_delete')
                                    <form action="{{ route('admin.external-connected-entities.destroy', $externalConnectedEntity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('external_connected_entity_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.external-connected-entities.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 100, stateSave: true,
  });
  let table = $('.datatable-ExternalConnectedEntity:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
