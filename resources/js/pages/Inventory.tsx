import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useState } from 'react';

const dummyData = [
    { id: 1, product: 'Flour', instructions: 'Order new stocks', available: '64/120', status: 'Low' },
    { id: 2, product: 'Sugar', instructions: 'Maintain stock', available: '120/120', status: 'Full' },
    { id: 3, product: 'Salt', instructions: 'Reduce order', available: '160/120', status: 'Overstock' },
    { id: 4, product: 'Oil', instructions: 'Order new stocks', available: '40/120', status: 'Low' },
];

export default function Inventory() {
    const [search, setSearch] = useState('');
    const [sortBy, setSortBy] = useState('id');

    const filteredData = dummyData
        .filter(item => item.product.toLowerCase().includes(search.toLowerCase()))
        .sort((a, b) => {
            if (sortBy === 'id') return a.id - b.id;
            if (sortBy === 'product') return a.product.localeCompare(b.product);
            return 0;
        });

    const statusColor = (status: string) => {
        if (status === 'Full') return 'text-green-600';
        if (status === 'Low') return 'text-yellow-600';
        if (status === 'Overstock') return 'text-red-600';
        return '';
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Inventory', href: '/inventory' }]}>
            <Head title="Inventory" />

            <div className="p-4 sm:p-6 lg:p-8">
                <div className="mb-4">
                    <h1 className="text-2xl font-semibold text-foreground">Inventory</h1>
                    <p className="text-sm text-muted-foreground">
                        Manage and view all available items in the system.
                    </p>
                </div>


                <div className="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 gap-3">
                    <div className="flex gap-2">
                        <Button variant="outline">Export to Excel</Button>
                        <Button>+ Update</Button>
                    </div>

                    <div className="flex gap-2">
                        <Input
                            placeholder="Search product..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />

                        <Select onValueChange={(value) => setSortBy(value)} defaultValue="id">
                            <SelectTrigger className="w-[180px]">
                                <SelectValue placeholder="Sort by" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="id">Menu ID</SelectItem>
                                <SelectItem value="product">Product</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div className="overflow-x-auto border rounded-lg">
                    <table className="min-w-full text-sm text-left table-auto">
                        <thead className="bg-dark-100">
                            <tr>
                                <th className="px-4 py-2 font-medium">Item ID</th>
                                <th className="px-4 py-2 font-medium">Product</th>
                                <th className="px-4 py-2 font-medium">Instructions</th>
                                <th className="px-4 py-2 font-medium">Available</th>
                                <th className="px-4 py-2 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredData.map((item) => (
                                <tr key={item.id} className="border-t">
                                    <td className="px-4 py-2">{item.id}</td>
                                    <td className="px-4 py-2">{item.product}</td>
                                    <td className="px-4 py-2">{item.instructions}</td>
                                    <td className="px-4 py-2">{item.available}</td>
                                    <td className={`px-4 py-2 font-semibold ${statusColor(item.status)}`}>{item.status}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AppLayout>
    );
}
