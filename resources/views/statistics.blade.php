<x-layout>
    <div class="py-5 holder text-center">
        <h2>Statistics</h2>
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>Number of Images</th>
                    <th>Total size</th>
                    <th>Replacement Policiy</th>
                    <th>Miss Rate</th>
                    <th>Hit Rate</th>
                    <th>Time</th>

                </tr>
            </thead>
            <tbody>
                @foreach($statistics as $statistic)
                <tr>
                    <td >{{$statistic['number_of_items']}}</td>
                    <td >{{$statistic['total_items_size']}}M</td>
                    <td >{{$statistic['policy'] == 2 ? 'Least Recently Used' : 'Random Replacment'}}</td>
                    <td >{{$statistic['miss_rate']}}%</td>
                    <td >{{$statistic['hit_rate']}}%</td>
                    <td >{{$statistic['created_at']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
