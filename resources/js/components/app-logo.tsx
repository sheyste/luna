import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    return (
        <>
            <div className="size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
                <img src="/images/blackLogo.png" alt="LUNA Logo" className="w-32 h-auto mb-6 rounded-md" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">Sol del luna</span>
            </div>
        </>
    );
}
