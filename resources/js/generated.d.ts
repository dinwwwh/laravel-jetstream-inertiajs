declare namespace App.Models {
    export type User = {
        readonly profile_photo_url: any;
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        password: string;
        remember_token: string | null;
        current_team_id: number | null;
        profile_photo_path: string | null;
        created_at: string | null;
        updated_at: string | null;
        two_factor_secret: string | null;
        two_factor_recovery_codes: string | null;
        tokens: any;
        notifications: any;
        readNotifications: any;
        unreadNotifications: any;
        readonly tokens_count: number;
        readonly notifications_count: number;
        readonly readNotifications_count: number;
        readonly unreadNotifications_count: number;
    };
}
