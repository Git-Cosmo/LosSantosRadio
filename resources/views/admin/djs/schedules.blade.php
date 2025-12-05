<x-admin.layouts.app>
    <x-slot name="title">DJ Schedule</x-slot>

    <div class="admin-header">
        <h1>{{ $djProfile->stage_name }} - Schedule</h1>
        <a href="{{ route('admin.djs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-2">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Current Schedule</h2>
            </div>
            <div class="card-body">
                @if($djProfile->schedules->count() > 0)
                    @foreach($djProfile->schedules as $schedule)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 6px; margin-bottom: 0.5rem;">
                            <div>
                                <strong>{{ $schedule->day_name }}</strong>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                                    {{ $schedule->formatted_time }}
                                    @if($schedule->show_name)
                                        · {{ $schedule->show_name }}
                                    @endif
                                </p>
                            </div>
                            <form action="{{ route('admin.djs.schedules.destroy', [$djProfile, $schedule]) }}" method="POST" onsubmit="return confirm('Remove this schedule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                        No schedule set. Add time slots below.
                    </p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Add Schedule</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.djs.schedules.store', $djProfile) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Day of Week *</label>
                        <select name="day_of_week" class="form-select" required>
                            @foreach($days as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Start Time *</label>
                            <input type="time" name="start_time" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Time *</label>
                            <input type="time" name="end_time" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Show Name (optional)</label>
                        <input type="text" name="show_name" class="form-input" placeholder="Override default show name">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Schedule
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
