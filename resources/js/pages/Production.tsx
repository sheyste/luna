import { useState } from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from '@/components/ui/select';

const dummyData = [
  { id: 1, menu: 'Burger', produced: 100, available: 30 },
  { id: 2, menu: 'Fries', produced: 200, available: 50 },
  { id: 3, menu: 'Hotdog', produced: 150, available: 70 },
];

type SortKey = 'menu' | 'produced' | 'available';

export default function Production() {
  const [search, setSearch] = useState('');
  const [sortKey, setSortKey] = useState<SortKey>('menu');

  const filteredData = dummyData
    .filter(item =>
      item.menu.toLowerCase().includes(search.toLowerCase())
    )
    .sort((a, b) => {
      if (sortKey === 'menu') return a.menu.localeCompare(b.menu);
      return (a[sortKey] as number) - (b[sortKey] as number);
    });

  return (
    <AppLayout breadcrumbs={[{ title: 'Production', href: '/production' }]}>
      <Head title="Production" />

      <div className="p-4 sm:p-6 lg:p-8">
        <div className="mb-4">
          <h1 className="text-2xl font-semibold text-foreground">Production</h1>
          <p className="text-sm text-muted-foreground">
            Manage production records and availability of menu items.
          </p>
        </div>

        <div className="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex gap-2">
            <Button variant="outline">Export to Excel</Button>
            <Button>+ Update</Button>
          </div>

          <div className="flex gap-2">
            <Select onValueChange={value => setSortKey(value as SortKey)} defaultValue="menu">
              <SelectTrigger className="w-[150px]">
                <SelectValue placeholder="Sort by" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="menu">Menu</SelectItem>
                <SelectItem value="produced">Produced</SelectItem>
                <SelectItem value="available">Available</SelectItem>
              </SelectContent>
            </Select>
            <Input
              type="text"
              placeholder="Search menu..."
              value={search}
              onChange={e => setSearch(e.target.value)}
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm border border-border">
            <thead className="bg-muted text-foreground">
              <tr>
                <th className="px-4 py-2 border-b">Menu ID</th>
                <th className="px-4 py-2 border-b">Menu</th>
                <th className="px-4 py-2 border-b">Produced</th>
                <th className="px-4 py-2 border-b">Available</th>
              </tr>
            </thead>
            <tbody>
              {filteredData.map(item => (
                <tr key={item.id} className="border-t">
                  <td className="px-4 py-2">{item.id}</td>
                  <td className="px-4 py-2">{item.menu}</td>
                  <td className="px-4 py-2">{item.produced}</td>
                  <td className="px-4 py-2">{item.available}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </AppLayout>
  );
}
