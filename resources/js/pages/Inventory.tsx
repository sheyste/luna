import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardContent } from '@/components/ui/card';

export default function Inventory() {
    return (
        <AppLayout
            breadcrumbs={[{ title: 'Inventory', href: '/inventory' }]}
        >
            <Head title="Inventory" />

            <div className="p-4 sm:p-6 lg:p-8">
                <div className="mb-4">
                    <h1 className="text-2xl font-semibold text-foreground">Inventory</h1>
                    <p className="text-sm text-muted-foreground">
                        Manage and view all available items in the system.
                    </p>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <Card>
                        <CardContent className="p-4">
                            <div className="text-lg font-medium text-foreground">Total Items</div>
                            <div className="text-2xl font-bold text-foreground">123</div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent className="p-4">
                            <div className="text-lg font-medium text-foreground">Out of Stock</div>
                            <div className="text-2xl font-bold text-foreground">5</div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
