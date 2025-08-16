import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Property {
    id: number;
    name: string;
    description: string;
    type: string;
    city: string;
    state: string;
    country: string;
    max_guests: number;
    bedrooms: number;
    bathrooms: number;
    price_per_night: number;
    photos: string[] | null;
    amenities: string[] | null;
    accommodation_owner: {
        business_name: string;
        user: {
            name: string;
        };
    };
    reviews_count?: number;
    average_rating?: number;
}

interface Props {
    properties: {
        data: Property[];
        links: Array<{
            url?: string;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
    };
    cities: string[];
    types: string[];
    filters: {
        search?: string;
        city?: string;
        type?: string;
        guests?: number;
        min_price?: number;
        max_price?: number;
    };
    [key: string]: unknown;
}

export default function PropertiesIndex({ properties, cities, types, filters }: Props) {
    const [searchForm, setSearchForm] = useState({
        search: filters.search || '',
        city: filters.city || '',
        type: filters.type || '',
        guests: filters.guests || '',
        min_price: filters.min_price || '',
        max_price: filters.max_price || '',
    });

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/properties', searchForm, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilters = () => {
        const emptyFilters = {
            search: '',
            city: '',
            type: '',
            guests: '',
            min_price: '',
            max_price: '',
        };
        setSearchForm(emptyFilters);
        router.get('/properties', emptyFilters);
    };

    const renderStars = (rating: number) => {
        return '⭐'.repeat(Math.round(rating));
    };

    return (
        <AppShell>
            <Head title="Browse Properties" />
            
            <div className="container mx-auto px-4 py-8">
                {/* Header */}
                <div className="flex justify-between items-center mb-8">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">🏠 Browse Properties</h1>
                        <p className="text-gray-600 mt-2">Discover amazing accommodations for your next stay</p>
                    </div>
                </div>

                {/* Search and Filters */}
                <Card className="mb-8">
                    <CardHeader>
                        <CardTitle className="text-lg">🔍 Search & Filter</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSearch} className="space-y-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <Label htmlFor="search">Search</Label>
                                    <Input
                                        id="search"
                                        placeholder="Property name, city, type..."
                                        value={searchForm.search}
                                        onChange={(e) => setSearchForm({ ...searchForm, search: e.target.value })}
                                    />
                                </div>
                                
                                <div>
                                    <Label htmlFor="city">City</Label>
                                    <Select
                                        value={searchForm.city}
                                        onValueChange={(value) => setSearchForm({ ...searchForm, city: value })}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Any city" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="">Any city</SelectItem>
                                            {cities.map((city) => (
                                                <SelectItem key={city} value={city}>
                                                    {city}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div>
                                    <Label htmlFor="type">Property Type</Label>
                                    <Select
                                        value={searchForm.type}
                                        onValueChange={(value) => setSearchForm({ ...searchForm, type: value })}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Any type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="">Any type</SelectItem>
                                            {types.map((type) => (
                                                <SelectItem key={type} value={type}>
                                                    {type}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div>
                                    <Label htmlFor="guests">Guests</Label>
                                    <Input
                                        id="guests"
                                        type="number"
                                        placeholder="Number of guests"
                                        min="1"
                                        value={searchForm.guests}
                                        onChange={(e) => setSearchForm({ ...searchForm, guests: e.target.value })}
                                    />
                                </div>

                                <div>
                                    <Label htmlFor="min_price">Min Price/Night</Label>
                                    <Input
                                        id="min_price"
                                        type="number"
                                        placeholder="$0"
                                        min="0"
                                        value={searchForm.min_price}
                                        onChange={(e) => setSearchForm({ ...searchForm, min_price: e.target.value })}
                                    />
                                </div>

                                <div>
                                    <Label htmlFor="max_price">Max Price/Night</Label>
                                    <Input
                                        id="max_price"
                                        type="number"
                                        placeholder="$1000"
                                        min="0"
                                        value={searchForm.max_price}
                                        onChange={(e) => setSearchForm({ ...searchForm, max_price: e.target.value })}
                                    />
                                </div>
                            </div>

                            <div className="flex gap-4">
                                <Button type="submit">
                                    🔍 Search Properties
                                </Button>
                                <Button type="button" variant="outline" onClick={clearFilters}>
                                    🗑️ Clear Filters
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                {/* Results */}
                <div className="mb-6">
                    <p className="text-gray-600">
                        Found {properties.data.length} properties
                        {Object.values(filters).some(value => value) && ' matching your criteria'}
                    </p>
                </div>

                {/* Properties Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {properties.data.map((property) => (
                        <Card key={property.id} className="hover:shadow-lg transition-shadow">
                            <CardHeader>
                                <div className="aspect-video bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg mb-4 flex items-center justify-center">
                                    <span className="text-4xl">🏠</span>
                                </div>
                                <CardTitle className="text-lg">{property.name}</CardTitle>
                                <CardDescription className="line-clamp-2">
                                    {property.description}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-3">
                                    <div className="flex items-center gap-2">
                                        <Badge variant="secondary">{property.type}</Badge>
                                        <span className="text-sm text-gray-600">
                                            📍 {property.city}, {property.state}
                                        </span>
                                    </div>

                                    <div className="grid grid-cols-3 gap-2 text-sm">
                                        <span>👥 {property.max_guests} guests</span>
                                        <span>🛏️ {property.bedrooms} beds</span>
                                        <span>🚿 {property.bathrooms} baths</span>
                                    </div>

                                    {property.amenities && property.amenities.length > 0 && (
                                        <div className="flex flex-wrap gap-1">
                                            {property.amenities.slice(0, 3).map((amenity, index) => (
                                                <Badge key={index} variant="outline" className="text-xs">
                                                    {amenity}
                                                </Badge>
                                            ))}
                                            {property.amenities.length > 3 && (
                                                <Badge variant="outline" className="text-xs">
                                                    +{property.amenities.length - 3} more
                                                </Badge>
                                            )}
                                        </div>
                                    )}

                                    <div className="flex items-center justify-between">
                                        <div className="text-lg font-semibold">
                                            ${property.price_per_night}/night
                                        </div>
                                        {property.average_rating && (
                                            <div className="text-sm">
                                                {renderStars(property.average_rating)} 
                                                ({property.reviews_count} reviews)
                                            </div>
                                        )}
                                    </div>

                                    <Link href={`/properties/${property.id}`}>
                                        <Button className="w-full">
                                            👁️ View Details
                                        </Button>
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Pagination */}
                {properties.last_page > 1 && (
                    <div className="mt-8 flex justify-center gap-2">
                        {properties.links.map((link, index) => {
                            if (!link.url) {
                                return (
                                    <Button key={index} variant="outline" disabled>
                                        {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                                    </Button>
                                );
                            }
                            
                            return (
                                <Link key={index} href={link.url}>
                                    <Button 
                                        variant={link.active ? "default" : "outline"}
                                        size="sm"
                                    >
                                        {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                                    </Button>
                                </Link>
                            );
                        })}
                    </div>
                )}

                {/* No results */}
                {properties.data.length === 0 && (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🔍</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">
                            No properties found
                        </h3>
                        <p className="text-gray-600 mb-4">
                            Try adjusting your search criteria or clearing filters
                        </p>
                        <Button onClick={clearFilters} variant="outline">
                            🗑️ Clear All Filters
                        </Button>
                    </div>
                )}
            </div>
        </AppShell>
    );
}