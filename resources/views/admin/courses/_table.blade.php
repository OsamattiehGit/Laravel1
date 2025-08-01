<thead>
  <tr>
    <th>Title</th>
    <th>Category</th>
    <th>Status</th>
    <th>Instructor</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
  @foreach($courses as $c)
    <tr>
      <td>{{ $c->title }}</td>
      <td>{{ $c->category->name ?? '—' }}</td>
      <td>{{ ucfirst($c->status) }}</td>
      <td>{{ $c->instructor }}</td>
      <td>
        <a href="{{ route('admin.courses.edit', $c) }}"
           class="admin-btn admin-btn-secondary"
        >Edit</a>

        <form
          action="{{ route('admin.courses.destroy', $c) }}"
          method="POST"
          style="display:inline-block;"
          onsubmit="return confirm('Delete this course?')"
        >
          @csrf @method('DELETE')
          <button type="submit" class="admin-btn admin-btn-secondary">
            Delete
          </button>
        </form>
      </td>
    </tr>
  @endforeach
</tbody>
