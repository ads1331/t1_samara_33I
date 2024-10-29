@include('layouts.header')
<head>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<div class="container lk-wrapp">
    <div class="lk-title">
        <p class="lk-name">{{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Количество созданных отчетов: <a href="#projects" class="lk-projects">{{ $reports->count() }}</a></p>
        <p>Количество активных проектов: <a href="#active" class="lk-projects">{{ $tasks->count() }}</a></p>
    </div>
    <h2>Активные проекты (Диаграмма Ганта)</h2>
    <div class="active-projects ganta">
    @if ($tasks->isEmpty())
        <p>У вас нет активных проектов.</p>
    @else
        <table class="table" id="active">
            <thead>
                <tr>
                    <th>Название проекта</th>
                    <th>Длительность</th>
                    <th>Дата начала</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks->take(3) as $task) 
                    <tr>
                        <td>{{ $task->text }}</td>
                        <td>{{ $task->duration }}</td>
                        <td>{{ $task->start_date }}</td>
                        <td>
                            <a href="/gantt" class="btn btn-primary see">Просмотр</a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($tasks->count() > 3)
            <button id="show-more-tasks" class="btn btn-secondary btn-more">Смотреть все</button>
            <div id="more-tasks" style="display: none;">
                <table class="table">
                    <tbody>
                        @foreach ($tasks->slice(3) as $task) 
                            <tr>
                                <td>{{ $task->text }}</td>
                                <td>{{ $task->duration }}</td>
                                <td>{{ $task->progress }}</td>
                                <td>{{ $task->start_date }}</td>
                                <td>
                                    <a href="/gantt" class="btn btn-primary see">Просмотр</a> 
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
    </div>
    <h2>История отчетов</h2>
    <div class="active-projects history">
    @if ($reports->isEmpty())
        <p>У вас нет отчетов.</p>
    @else
         <!-- Количество отчетов -->
        <table class="table" id="projects">
            <thead>
                <tr>
                    <th>Название проекта</th>
                    <th>Описание</th>
                    <th>Тип отчета</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports->take(3) as $report) 
                    <tr>
                        <td>{{ $report->project_name }}</td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->report_type }}</td>
                        <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('report.show', $report->id) }}" class="btn btn-primary see">Просмотр</a> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($reports->count() > 3)
            <button id="show-more" class="btn btn-secondary btn-more">Смотреть все</button>
            <div id="more-reports" style="display: none;">
                <table class="table">
                    <tbody>
                        @foreach ($reports->slice(3) as $report) 
                            <tr>
                                <td>{{ $report->project_name }}</td>
                                <td>{{ $report->description }}</td>
                                <td>{{ $report->report_type }}</td>
                                <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('report.show', $report->id) }}" class="btn btn-primary see">Просмотр</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
</div>

@section('scripts')
    <script>
        // Скрипт для показа дополнительных задач
        document.getElementById('show-more-tasks').addEventListener('click', function() {
            document.getElementById('more-tasks').style.display = 'block'; 
            this.style.display = 'none'; 
        });

        // Скрипт для показа дополнительных отчетов
        document.getElementById('show-more').addEventListener('click', function() {
            document.getElementById('more-reports').style.display = 'block'; 
            this.style.display = 'none'; 
        });
    </script>
@endsection
