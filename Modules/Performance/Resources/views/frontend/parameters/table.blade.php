<div class="table-responsive">
      @if($parameters && count($parameters)>0)
        <table class="table table-sm table-fixed">
            <thead>
              <tr>
                <th scope="row" class="fixed-column bg-secondary" style="width: 250px;">PARAMETER</th>
                @foreach($parameters as $parameter)
                  <th scope="col">{{ $parameter->unit->name}}
                    <small>{{\Carbon\Carbon::parse($parameter->date)->format('M Y')}}</small>
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($param_points as $key => $param_point)
                <tr>
                  <th scope="row" class="fixed-column bg-light-gray" >{{$param_point}}</th>
                  @foreach($parameters as $parameter)
                      <td scope="col">{{ $parameter->$key}}</td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
        </table>
      @else
        <tr>
          <td>No data available</td>
        </tr>
      @endif
    </div>