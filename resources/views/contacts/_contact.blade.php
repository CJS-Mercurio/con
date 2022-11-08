<tr>
  <th scope="row">{{ $contacts->firstItem() + $index }}</th>
  <td>{{ $contact->first_name }}</td>
  <td>{{ $contact->last_name }}</td>
  <td>{{ $contact->email }}</td>
  <td>{{ $contact->company->name }}</td>
  <td width="150">
  <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-sm btn-circle btn-outline-info" title="Show"><i class="fa fa-eye"></i></a>
  <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-sm btn-circle btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>
  <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display: inline">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-sm btn-circle btn-outline-danger" title="Delete"><i class="fa fa-trash"></i></button>
  </form>
  </td>
</tr>