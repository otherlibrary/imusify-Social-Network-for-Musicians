import { environment } from '../environments/environment';

export class AppConfig {

    /* Base URL for API */
    public readonly apiUrl = environment.host;
    public readonly gender = [
        { value: 'male', label: 'Male' },
        { value: 'female', label: 'Female' }
    ]

    public readonly errorMessages = {
        first_name:   'Please, type a first name',
        last_name:    'Please, fill this field',
        username:     'Username is required',
        password:     'Password is required and must be at least 4 characters long.',
        password_len: 'Password must be at least 4 characters long.',
        email:        'Please, check an email',
        gender:       'Choose a gender. This field is a necessary',
        agree:        'Please, read and agree our "Terms of Use Agreement"'
    }
}