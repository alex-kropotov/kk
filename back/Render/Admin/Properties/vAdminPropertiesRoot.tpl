
<div class="container-fluid py-4 px-4 mx-auto">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Property Dashboard</h1>
        <div>
            <button class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Add New Property
            </button>
            <button class="btn btn-primary" disabled>
                <i class="bi bi-download"></i> Export Selected (0)
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col" width="50">Select</th>
                    <th scope="col">Code</th>
                    <th scope="col">Type</th>
                    <th scope="col">Location</th>
                    <th scope="col">Price</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>RENT001</td>
                    <td>house (rent)</td>
                    <td>Београд, Земун</td>
                    <td>€800</td>
                    <td><span class="badge bg-success bg-opacity-10 text-success status-badge">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger action-btn me-1">Deactivate</button>
                        <button class="btn btn-sm btn-outline-primary action-btn">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>SALE001</td>
                    <td>apartment (sale)</td>
                    <td>Београд, Нови Београд</td>
                    <td>€150&nbsp;000</td>
                    <td><span class="badge bg-success bg-opacity-10 text-success status-badge">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger action-btn me-1">Deactivate</button>
                        <button class="btn btn-sm btn-outline-primary action-btn">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>SALE250122814</td>
                    <td>apartment (sale)</td>
                    <td>, </td>
                    <td>€0</td>
                    <td><span class="badge bg-success bg-opacity-10 text-success status-badge">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger action-btn me-1">Deactivate</button>
                        <button class="btn btn-sm btn-outline-primary action-btn">Edit</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
