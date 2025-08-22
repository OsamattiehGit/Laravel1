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

        <button
          type="button"
          class="admin-btn admin-btn-secondary"
          onclick="deleteCourse({{ $c->id }})"
        >
          Delete
        </button>
      </td>
    </tr>
  @endforeach
</tbody>
