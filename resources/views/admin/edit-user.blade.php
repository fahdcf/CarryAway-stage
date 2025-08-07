<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier utilisateur - CarryWay Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        .container { max-width: 500px; margin: 40px auto; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,0.05); padding: 2rem; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.25rem; font-size: 1rem; }
        .btn { background: #0f172a; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        .btn:hover { background: #1e293b; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #0369a1; text-decoration: none; font-size: 0.95rem; }
        .back-link:hover { text-decoration: underline; }
        .error { color: #dc2626; font-size: 0.95rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Retour au tableau de bord</a>
        <h1>Modifier l'utilisateur</h1>
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            <label for="city">Ville</label>
            <input type="text" id="city" name="city" value="{{ old('city', $user->userDetail ? $user->userDetail->city : '') }}">
            <button type="submit" class="btn">Enregistrer</button>
        </form>
    </div>
</body>
</html>
