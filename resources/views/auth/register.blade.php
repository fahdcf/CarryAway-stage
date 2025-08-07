<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Créer un compte - CarryWay</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out 0.2s forwards;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: #0f172a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .logo-text {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1rem;
            color: #475569;
            line-height: 1.5;
        }

        .form-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
            border: 1px solid #f1f5f9;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 25%;
            right: 25%;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            background: #f1f5f9;
            color: #94a3b8;
            border: 2px solid #e2e8f0;
        }

        .step.active .step-circle {
            background: #0f172a;
            color: white;
            border-color: #0f172a;
        }

        .step.completed .step-circle {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .step-label {
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
        }

        .step.active .step-label {
            color: #0f172a;
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #0f172a;
            background: white;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }

        .form-input.error, .form-select.error {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            color: #64748b;
        }

        .file-input-label:hover {
            border-color: #0f172a;
            background: white;
        }

        .file-input-label.has-file {
            border-color: #059669;
            background: #ecfdf5;
            color: #059669;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #0f172a;
            color: white;
        }

        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        }

        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 0.875rem;
            color: #64748b;
        }

        .link {
            color: #0f172a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .link:hover {
            color: #475569;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-card {
                padding: 1.5rem;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg>
                </div>
                <span class="logo-text">CarryWay</span>
            </div>
            <h1>Créer un compte</h1>
            <p>Rejoignez CarryWay et commencez à envoyer ou transporter des colis</p>
        </div>

        <!-- Registration Form -->
        <div class="form-card">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step-1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Informations</div>
                </div>
                <div class="step" id="step-2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Documents</div>
                </div>
                <div class="step" id="step-3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Sécurité</div>
                </div>
            </div>

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data" id="registrationForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="form-section active" id="section-1">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Nom complet *</label>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   value="{{ old('name') }}" 
                                   required 
                                   class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                                   placeholder="Votre nom complet">
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input id="phone" 
                                   name="phone" 
                                   type="tel" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                                   placeholder="06 12 34 56 78">
                            @error('phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Adresse email *</label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}" 
                               required 
                               class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               placeholder="votre@email.com">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="birth_date" class="form-label">Date de naissance *</label>
                            <input id="birth_date" 
                                   name="birth_date" 
                                   type="date" 
                                   value="{{ old('birth_date') }}" 
                                   required 
                                   class="form-input {{ $errors->has('birth_date') ? 'error' : '' }}">
                            @error('birth_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gender" class="form-label">Genre *</label>
                            <select id="gender" 
                                    name="gender" 
                                    required 
                                    class="form-select {{ $errors->has('gender') ? 'error' : '' }}">
                                <option value="">Sélectionner</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                            </select>
                            @error('gender')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">Ville *</label>
                            <input id="city" 
                                   name="city" 
                                   type="text" 
                                   value="{{ old('city') }}" 
                                   required 
                                   class="form-input {{ $errors->has('city') ? 'error' : '' }}"
                                   placeholder="Casablanca">
                            @error('city')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cin" class="form-label">Numéro CIN *</label>
                            <input id="cin" 
                                   name="cin" 
                                   type="text" 
                                   value="{{ old('cin') }}" 
                                   required 
                                   class="form-input {{ $errors->has('cin') ? 'error' : '' }}"
                                   placeholder="AB123456">
                            @error('cin')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Adresse complète *</label>
                        <input id="address" 
                               name="address" 
                               type="text" 
                               value="{{ old('address') }}" 
                               required 
                               class="form-input {{ $errors->has('address') ? 'error' : '' }}"
                               placeholder="123 Rue Example, Quartier, Ville">
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Step 2: Documents -->
                <div class="form-section" id="section-2">
                    <div class="form-group">
                        <label class="form-label">Photo de profil (optionnel)</label>
                        <div class="file-input-wrapper">
                            <input type="file"
                                   name="profile_picture"
                                   accept="image/*"
                                   class="file-input"
                                   onchange="updateFileLabel(this, 'profile-label')">
                            <div class="file-input-label" id="profile-label">
                                <div>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7,10 12,15 17,10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <div>Cliquez pour télécharger votre photo</div>
                                    <div style="font-size: 0.75rem; margin-top: 0.25rem;">JPG, PNG (max 2MB)</div>
                                </div>
                            </div>
                        </div>
                        @error('profile_picture')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Recto CIN *</label>
                            <div class="file-input-wrapper">
                                <input type="file"
                                       name="id_document_front"
                                       accept="image/*"
                                       required
                                       class="file-input"
                                       onchange="updateFileLabel(this, 'front-label')">
                                <div class="file-input-label" id="front-label">
                                    <div>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7,10 12,15 17,10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        <div>Recto de votre CIN</div>
                                        <div style="font-size: 0.75rem; margin-top: 0.25rem;">JPG, PNG (max 5MB)</div>
                                    </div>
                                </div>
                            </div>
                            @error('id_document_front')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Verso CIN *</label>
                            <div class="file-input-wrapper">
                                <input type="file"
                                       name="id_document_back"
                                       accept="image/*"
                                       required
                                       class="file-input"
                                       onchange="updateFileLabel(this, 'back-label')">
                                <div class="file-input-label" id="back-label">
                                    <div>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7,10 12,15 17,10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        <div>Verso de votre CIN</div>
                                        <div style="font-size: 0.75rem; margin-top: 0.25rem;">JPG, PNG (max 5MB)</div>
                                    </div>
                                </div>
                            </div>
                            @error('id_document_back')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 3: Security -->
                <div class="form-section" id="section-3">
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input id="password"
                               name="password"
                               type="password"
                               required
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="••••••••">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               required
                               class="form-input"
                               placeholder="••••••••">
                    </div>

                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin: 1.5rem 0; font-size: 0.875rem; color: #475569;">
                        <p><strong>Note importante :</strong> Votre compte sera en attente d'approbation par un administrateur après l'inscription. Vous recevrez une notification une fois votre compte approuvé.</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Précédent
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                        Suivant
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 0.5rem;">
                            <polyline points="9,18 15,12 9,6"></polyline>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="text-center" style="margin-top: 1.5rem;">
                <p class="text-sm">
                    Vous avez déjà un compte ?
                    <a href="{{ route('login') }}" class="link">Se connecter</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function updateFileLabel(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files[0]) {
                label.classList.add('has-file');
                label.innerHTML = `
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                        <div>${input.files[0].name}</div>
                        <div style="font-size: 0.75rem; margin-top: 0.25rem;">Fichier sélectionné</div>
                    </div>
                `;
            }
        }

        function changeStep(direction) {
            const newStep = currentStep + direction;

            if (newStep < 1 || newStep > totalSteps) return;

            // Hide current step
            document.getElementById(`section-${currentStep}`).classList.remove('active');
            document.getElementById(`step-${currentStep}`).classList.remove('active');

            // Show new step
            currentStep = newStep;
            document.getElementById(`section-${currentStep}`).classList.add('active');
            document.getElementById(`step-${currentStep}`).classList.add('active');

            // Mark previous steps as completed
            for (let i = 1; i < currentStep; i++) {
                document.getElementById(`step-${i}`).classList.add('completed');
            }

            // Update buttons
            document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'flex';

            const nextBtn = document.getElementById('nextBtn');
            if (currentStep === totalSteps) {
                nextBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                        <polyline points="20,6 9,17 4,12"></polyline>
                    </svg>
                    Créer mon compte
                `;
                nextBtn.type = 'submit';
                nextBtn.onclick = null;
            } else {
                nextBtn.innerHTML = `
                    Suivant
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 0.5rem;">
                        <polyline points="9,18 15,12 9,6"></polyline>
                    </svg>
                `;
                nextBtn.type = 'button';
                nextBtn.onclick = () => changeStep(1);
            }
        }
    </script>
</body>
</html>
