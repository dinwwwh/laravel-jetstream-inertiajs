declare namespace App.Models {
    export type User = {
        readonly profile_photo_url: any;
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        password: string;
        two_factor_secret: string | null;
        two_factor_recovery_codes: string | null;
        remember_token: string | null;
        current_team_id: number | null;
        profile_photo_path: string | null;
        created_at: string | null;
        updated_at: string | null;
        tokens: any;
        notifications: any;
        readNotifications: any;
        unreadNotifications: any;
        readonly tokens_count: number;
        readonly notifications_count: number;
        readonly read_notifications_count: number;
        readonly unread_notifications_count: number;
    };
}
